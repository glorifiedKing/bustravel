<?php

namespace glorifiedking\BusTravel\Http\Controllers;

use Carbon\Carbon;
use glorifiedking\BusTravel\Route;
use glorifiedking\BusTravel\RoutesDepartureTime;
use glorifiedking\BusTravel\RoutesStopoversDepartureTime;
use glorifiedking\BusTravel\Station;
use glorifiedking\BusTravel\Faq;
use glorifiedking\BusTravel\StopoverStation;
use glorifiedking\BusTravel\PaymentTransaction;
use glorifiedking\BusTravel\OperatorPaymentMethod;
use glorifiedking\BusTravel\Booking;
use glorifiedking\BusTravel\SmsTemplate;
use glorifiedking\BusTravel\EmailTemplate;
use glorifiedking\BusTravel\Operator;
use glorifiedking\BusTravel\CreditTransaction;
use glorifiedking\BusTravel\Mail\TicketEmail;
use glorifiedking\BusTravel\Events\TransactionStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Auth;
use glorifiedking\BusTravel\GeneralSetting;
use glorifiedking\BusTravel\Jobs\ProcessDebitCallback;
use glorifiedking\BusTravel\Jobs\ProcessCreditCallback;

class FrontendController extends Controller
{
    public function __construct()
    {
        $this->middleware('web')->only('checkout','get_payment_status');
        $this->middleware('auth')->only('checkout','process_payment','get_payment_status');
        $this->middleware('bt_key')->only('process_payment_callback','credit_request_callback');
    }

    public function homepage()
    {
        $bus_stations = Station::all();

        return view('bustravel::frontend.index', compact('bus_stations'));
    }

    public function search_routes(Request $request)
    {
        $last_date = Carbon::now()->addWeeks(2)->toDateString();
        $validated_data = $request->validate([
            'to_station'        => 'required|numeric|different:departure_station',
            'departure_station' => 'required|numeric',
            'date_of_travel'    => "required|date|after:yesterday|before:$last_date",
            'time_of_travel'    => 'required',
            'adults'            => 'numeric|min:1',
        ]);

        //search

        // get which day of the week it is for date 
        $travel_day_of_week = Carbon::parse($request->date_of_travel)->format('l');
        $travel_time = Carbon::parse($request->time_of_travel)->format('G:i');
        $no_of_tickets = $request->adults;
        //dd($travel_day_of_week);
        $departure_time = RoutesDepartureTime::where('days_of_week', 'like', "%$travel_day_of_week%")->whereTime('departure_time','>',$travel_time)->get()->sortBy('departure_time');   
        //dd($departure_time);
        // first search for main route 
        $route_results = Route::with(['departure_times' => function ($query) use($travel_day_of_week,$travel_time) {
            $query->where('days_of_week', 'like', "%$travel_day_of_week%")->whereTime('departure_time','>',$travel_time);
        }])->where([
            ['start_station', '=', $request->departure_station],
            ['end_station', '=', $request->to_station],            
        ])->get()->sortBy('departure_time');
        //dd($route_results);  
        if ($route_results->isEmpty()) {

            //check if bus is full

            //filter by time
        }
        $stop_over_routes = StopoverStation::with(['departure_times' => function ($query) use($travel_day_of_week,$travel_time) {
            
            $query->whereHas('main_route_departure_time', function ($query) use($travel_day_of_week) {
                $query->where('days_of_week', 'like', "%$travel_day_of_week%");
            });
            $query->whereTime('arrival_time','>',$travel_time);
        },
        
        ])->where([
            ['start_station', '=', $request->departure_station],
            ['end_station', '=', $request->to_station],            
        ])->get()->sortBy('departure_time');
        $date_of_travel = Carbon::parse($request->date_of_travel)->format('Y-m-d');

        return view('bustravel::frontend.route_search_results', compact('route_results','stop_over_routes', 'date_of_travel','no_of_tickets'));
    }

    public function cart(Request $request)
    {
        //get cart items
        $cart = $request->session()->get('cart.items') ?? [];
        $main_route_ids = [0];
        $stop_over_route_ids = [0];
        if(!empty($cart))
        {
            foreach($cart as $c)
            {
                if($c['route_type'] == 'main_route')
                {
                    $main_route_ids[] = (int)$c['id'];
                }
                else if($c['route_type'] == 'stop_over_route')
                {
                    $stop_over_route_ids[] = (int)$c['id'];
                }
            }
            
        }
        
        $main_route_departures = RoutesDepartureTime::whereIn('id', $main_route_ids)->get();
        $stop_over_route_departures = RoutesStopoversDepartureTime::whereIn('id',$stop_over_route_ids)->get();

        return view('bustravel::frontend.cart', compact('main_route_departures','stop_over_route_departures'));
    }

    public function add_to_basket(Request $request, $route_departure_time_id, $date_of_travel,$route_type,$quantity=1)
    {

        if($route_type == 'main_route')
        {
            $route_time = RoutesDepartureTime::findOrFail($route_departure_time_id);
            
        }
        else if($route_type == 'stop_over_route')
        {
            $route_time = RoutesStopoversDepartureTime::findOrFail($route_departure_time_id);
        }
        $operator_id = $route_time->route->operator->id ?? $route_time->route->route->operator->id;
        //dd($request->session());
        try
        {
            if ($request->session()->has('cart')) {
                if (!in_array($route_departure_time_id, array_column($request->session()->get('cart.items'), 'id'))) {
                    $request->session()->push('cart.items', [
                         'id'             => $route_departure_time_id,
                         'quantity'       => $quantity,
                         'amount'         => $route_time->route->price,
                         'date_of_travel' => $date_of_travel,
                         'route_type'     => $route_type,
                         'operator_id'    => $operator_id,  
                    ]);
                }
                if (in_array($route_departure_time_id, array_column($request->session()->get('cart.items'), 'id'))) {
                    // get current quantity
                    $cart_items = $request->session()->get('cart.items');
                    $session_key = array_search($route_departure_time_id,array_column($request->session()->get('cart.items'), 'id'));
                    $old_quantity = $cart_items[$session_key]['quantity'];
                    $new_quantity = $old_quantity + $quantity;
                    if($new_quantity > 0)
                    {
                        $request->session()->put("cart.items.$session_key.quantity",$new_quantity);
                    }
                    else if($new_quantity == 0)
                    {
                        $request->session()->pull("cart.items.$session_key"); 
                    }
                }
            } elseif (!$request->session()->has('cart')) {
                $request->session()->put('cart.items', []);
                $request->session()->push('cart.items', [
                    'id'             => $route_departure_time_id,
                    'quantity'       => $quantity,
                    'amount'         => $route_time->route->price,
                    'date_of_travel' => $date_of_travel,
                    'route_type'     => $route_type,
                    'operator_id'    => $operator_id,
               ]);
            }

        }
        catch(\Exception $e)
        {
            $this->clear_cart($request);
        }
        

        return redirect()->route('bustravel.cart');
    }

    public function clear_cart(Request $request)
    {
        if ($request->session()->has('cart')) {
            $request->session()->forget('cart');
        }

        return redirect()->route('bustravel.homepage');
    }

    public function remove_cart_item(Request $request, $key)
    {
        if ($request->session()->has('cart')) {
            $request->session()->pull('cart.items', $key);
        }

        return redirect()->route('bustravel.cart');
    }

    public function checkout(Request $request)
    {
        $cart = session()->get('cart.items');
        //check for empty cart 
        if(!$cart)
        {
            return redirect()->route('bustravel.cart');
        }
        $sms_cost = GeneralSetting::where('setting_prefix','sms_cost_rw')->first()->setting_value ?? 9;
        return view('bustravel::frontend.checkout',compact('sms_cost'));
    }

    public function bus_times(Request $request)
    {
        $now = date('H:i');  
        $travel_day_of_week = date('l');      
        $routes_times =RoutesDepartureTime::where('days_of_week', 'like', "%$travel_day_of_week%")->whereTime('departure_time','>',$now)->get()->sortBy('departure_time');
        
        return view('bustravel::frontend.bus_times',compact('routes_times'));
    }
    public function stations(Request $request)
    {
        $stations =Station::orderBy('name','ASC')->paginate(10);
        return view('bustravel::frontend.stations',compact('stations'));
    }

    public function faqs(Request $request)
    {
        $faqs =Faq::paginate(10);
        return view('bustravel::frontend.faqs',compact('faqs'));
    }

    public function process_payment(Request $request)
    {
        //validate 
        $validated_data = $request->validate([
            'first_name'        => 'required|',
            'email'    => 'required_with:ticketdeliveryemail|nullable|email:filter',
            'ticketdeliveryemail'    => 'required_without:ticketdeliverysms',
            'address_1'    => 'required',
            'phone_number'  => 'requiredif:payment_method,mobile_money|phone:RW',
            'country' => 'required',
        ]);

        $language = $request->language ?? 'english';

        // get amount to pay 
        $amount = 0;
        $main_routes = array();
        $stop_over_routes = array();
        $cart = session()->get('cart.items');

        //check for empty cart 
        if(!$cart)
        {
            return redirect()->route('bustravel.cart');
        }
        foreach($cart as $item)
        {
            // for now use the first operator but in future every operator to have his own request
            $operator_id = $item['operator_id'];
            $date_of_travel = $item['date_of_travel'];
            $no_of_tickets = $item['quantity'];
            //get routes 
            if($item['route_type'] == 'main_route')
            {
                $main_routes[] = $item['id'];
            }
            else if($item['route_type'] == 'stop_over_route')
            {
                $stop_over_routes[] = $item['id'];
            }
            //add amount 
            $amount += $item['quantity']*$item['amount'];
        }
        $send_sms = (isset($request->ticketdeliverysms)) ? 1 : 0;

        $send_email = (isset($request->ticketdeliveryemail)) ? 1 : 0;
        //add sms amount
        if($send_sms == 1)
        {

            $sms_cost = GeneralSetting::where('setting_prefix','sms_cost_rw')->first()->setting_value ?? 9;   
            $amount += $sms_cost;
        }      
        
        //add payee details 
        $payee_reference = '';
        if($request->payment_method =="mobile_money")
        {
            $payee_reference = $request->phone_number ;
            //add 250 if it is not 
            if(strlen($payee_reference) < 12)
            {
                $payee_reference="250".substr($request->phone_number, -9);
            }
            
        }
        //purchasing user 
        $paying_user = 0;
        if(Auth::check())
        {
            $paying_user = Auth::user()->id;
        }
        
        //get default payment method of operator 
        $default_payment_method = OperatorPaymentMethod::where([
            ['operator_id','=', $operator_id],
            ['is_default','=', '1'],
        ])->first();

        //abort if operator has no payment method
        $phone_number = $no = "250".substr($request->phone_number, -9); 

        // start transaction in trasaction table 
        $payment_transaction = new PaymentTransaction;
        $payment_transaction->payee_reference = $payee_reference;
        $payment_transaction->amount = $amount;
        $payment_transaction->main_routes = $main_routes;
        $payment_transaction->stop_over_routes = $stop_over_routes;
        $payment_transaction->user_id = $paying_user;
        $payment_transaction->first_name = $request->first_name;
        $payment_transaction->last_name = $request->last_name;
        $payment_transaction->phone_number = $phone_number;
        $payment_transaction->email = $request->email;
        $payment_transaction->address_1 = $request->address_1;
        $payment_transaction->address_2 = $request->address_2;
        $payment_transaction->country = 'RW';
        $payment_transaction->send_sms = $send_sms;
        $payment_transaction->send_email = $send_email;
        $payment_transaction->date_of_travel = $date_of_travel;
        $payment_transaction->transport_operator_id = $operator_id;
        $payment_transaction->no_of_tickets = $no_of_tickets;
        $payment_transaction->language = $language;
        $payment_transaction->save();

        // dd($payment_transaction);   
        // send request if payment method is mtn mobile money 
        $base_api_url = config('bustravel.payment_gateways.mtn_rw.url');    
        // send json request 
        $request_uri = $base_api_url."/makedebitrequest";
        $client = new \GuzzleHttp\Client(['decode_content' => false]);
        $debit_request = $client->request('POST', $request_uri, [ 
                              
                    'json'   => [
                        "token" =>"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE0OTk3",
                        "transaction_amount" => $amount,
                        "account_number" => "100023",
                        "payment_operator" => 1001,
                        "transaction_account" => $payee_reference,
                        "transaction_reference_number" => $payment_transaction->id,
                        "merchant_account" => "RW002",
                        "transaction_source" => "web",
                        "transaction_destination" => "web",
                        "transaction_reason" => "Bus Ticket payment",
                        "currency" => "RWF",
                    ]
                    ]);
        $code = $debit_request->getStatusCode(); 
        if($code == 200) 
        { 
           $response_body = json_decode($debit_request->getBody(),true);
                // log request
           $status_variables = var_export($response_body,true);
           $status_log = date('Y-m-d H:i:s')." transaction_id: ".$payment_transaction->id." WITH:".$status_variables."";
           //log the request 
           \Storage::disk('local')->append('payment_debit_request_log.txt',$status_log); 
            $new_transaction_status = $response_body['transaction_status'];
                //for success create ticket add to email and sms queue
                if($new_transaction_status == 'failed')
                {
                 // immediate failure 
                    $payment_transaction->status = 'failed';
                    $payment_transaction->payment_gateway_result = $response_body['status_code'];
                    $payment_transaction->save();
                    //$payment_transaction = $payment_transaction->refresh();
                    event(new TransactionStatusUpdated($payment_transaction));
                    
                }
        }
        // clear cart 
        if ($request->session()->has('cart')) {
            $request->session()->forget('cart');
        }

        // create notification 
        $notification_type = 'error';  
        $notification_message = 'Payment Error: Payment has not been successfull! Try again';            
         
        // wait 1 minute and call check status// for final result of payment  
    
            $notification = array(
                'type' => $notification_type,
                'message' => $notification_message
            ); 
            $transactionId = $payment_transaction->id;

            return view('bustravel::frontend.notification',compact('notification','transactionId'));
                   

          
    }

    public function process_payment_callback(Request $request)
    {
        
        ProcessDebitCallback::dispatch($request);
        

        return response()->json([
            "status_code" => "200"
        ]);
    }

    public function credit_request_callback (Request $request)
    {
        ProcessCreditCallback::dispatch($request);
        
        return response()->json([
            "status_code" => "200"
        ]);
    }

    public function get_payment_status($id)
    {
        $transaction = PaymentTransaction::find($id);
        if(!$transaction)
        {
            return response()->json([
                'status' => 'error',
                'result' => 'transaction not found'
            ],200);
        }
        return response()->json([
            'status' => $transaction->status,
            'result' => $transaction->payment_gateway_result
        ],200);

    }
}
