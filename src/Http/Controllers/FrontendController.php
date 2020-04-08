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

class FrontendController extends Controller
{
    public function __construct()
    {
        $this->middleware('web')->only('checkout');
        $this->middleware('auth')->only('checkout');
    }

    public function homepage()
    {
        $bus_stations = Station::all();

        return view('bustravel::frontend.index', compact('bus_stations'));
    }

    public function search_routes(Request $request)
    {
        $validated_data = $request->validate([
            'to_station'        => 'required|numeric|different:departure_station',
            'departure_station' => 'required|numeric',
            'date_of_travel'    => 'required|date',
            'time_of_travel'    => 'required',
            'adults'            => 'numeric|min:1',
        ]);

        //search

        // get which day of the week it is for date 
        $travel_day_of_week = Carbon::parse($request->date_of_travel)->format('l');
        $travel_time = Carbon::parse($request->time_of_travel)->format('G:i');
        //dd($travel_day_of_week);
        $departure_time = RoutesDepartureTime::where('days_of_week', 'like', "%$travel_day_of_week%")->whereTime('departure_time','>',$travel_time)->get();   
        //dd($departure_time);
        // first search for main route 
        $route_results = Route::with(['departure_times' => function ($query) use($travel_day_of_week,$travel_time) {
            $query->where('days_of_week', 'like', "%$travel_day_of_week%")->whereTime('departure_time','>',$travel_time);
        }])->where([
            ['start_station', '=', $request->departure_station],
            ['end_station', '=', $request->to_station],            
        ])->get();
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
        ])->get();
        $date_of_travel = Carbon::parse($request->date_of_travel)->format('Y-m-d');

        return view('bustravel::frontend.route_search_results', compact('route_results','stop_over_routes', 'date_of_travel'));
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

    public function add_to_basket(Request $request, $route_departure_time_id, $date_of_travel,$route_type)
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
        if ($request->session()->has('cart')) {
            if (!in_array($route_departure_time_id, array_column($request->session()->get('cart.items'), 'id'))) {
                $request->session()->push('cart.items', [
                     'id'             => $route_departure_time_id,
                     'quantity'       => 1,
                     'amount'         => $route_time->route->price,
                     'date_of_travel' => $date_of_travel,
                     'route_type'     => $route_type,
                     'operator_id'    => $operator_id,  
                ]);
            }
        } elseif (!$request->session()->has('cart')) {
            $request->session()->put('cart.items', []);
            $request->session()->push('cart.items', [
                'id'             => $route_departure_time_id,
                'quantity'       => 1,
                'amount'         => $route_time->route->price,
                'date_of_travel' => $date_of_travel,
                'route_type'     => $route_type,
                'operator_id'    => $operator_id,
           ]);
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
        return view('bustravel::frontend.checkout');
    }

    public function bus_times(Request $request)
    {
        $routes_times =RoutesDepartureTime::paginate(10);
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
            'last_name' => 'required|',
            'email'    => 'email|requiredif:ticketdeliveryemail,email',
            'ticketdeliveryemail'    => 'required_without:ticketdeliverysms',
            'address_1'    => 'required',
            'phone_number'  => 'requiredif:payment_method,mobile_money|numeric|min:1',
            'country' => 'required',
        ]);

        // get amount to pay 
        $amount = 0;
        $main_routes = array();
        $stop_over_routes = array();
        $cart = session()->get('cart.items');
        foreach($cart as $item)
        {
            // for now use the first operator but in future every operator to have his own request
            $operator_id = $item['operator_id'];
            $date_of_travel = $item['date_of_travel'];
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
            $amount += $item['amount'];
        }
        $send_sms = (isset($request->ticketdeliverysms)) ? 1 : 0;

        $send_email = (isset($request->ticketdeliveryemail)) ? 1 : 0;
        //add sms amount
        if($send_sms == 1)
        {
            $sms_cost = 5;  // must get it from config later 
            $amount += $sms_cost;
        }      
        
        //add payee details 
        $payee_reference = '';
        if($request->payment_method =="mobile_money")
        {
            $payee_reference = $request->phone_number;
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

        // start transaction in trasaction table 
        $payment_transaction = new PaymentTransaction;
        $payment_transaction->payee_reference = $payee_reference;
        $payment_transaction->amount = $amount;
        $payment_transaction->main_routes = $main_routes;
        $payment_transaction->stop_over_routes = $stop_over_routes;
        $payment_transaction->user_id = $paying_user;
        $payment_transaction->first_name = $request->first_name;
        $payment_transaction->last_name = $request->last_name;
        $payment_transaction->phone_number = $request->phone_number;
        $payment_transaction->email = $request->email;
        $payment_transaction->address_1 = $request->address_1;
        $payment_transaction->address_2 = $request->address_2;
        $payment_transaction->country = 'RW';
        $payment_transaction->send_sms = $send_sms;
        $payment_transaction->send_email = $send_email;
        $payment_transaction->date_of_travel = $date_of_travel;
        $payment_transaction->transport_operator_id = $operator_id;
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
                        "merchant_account" => $default_payment_method->sp_phone_number,
                        "transaction_source" => "web",
                        "transaction_destination" => "web",
                        "transaction_reason" => "Bus Ticket payment",
                        "currency" => "RWF",
                    ]
                    ]);
                       
        // clear cart 
        if ($request->session()->has('cart')) {
            $request->session()->forget('cart');
        }

        // create notification 
        $notification_type = 'error';  
        $notification_message = 'Payment Error: Payment has not been successfull! Try again';            
         
        // wait 1 minute and call check status// for final result of payment  
 /*       sleep(60);    
        $request_uri = $base_api_url."/checktransactionstatus";
        try{
            $client = new \GuzzleHttp\Client(['verify' => false]);
            $checkstatus = $client->request('POST', $request_uri, [                    
                    'json'   => [
                        "token" =>"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE0OTk3",                        
                        "transaction_account" => $payee_reference,
                        "transaction_reference_number" => $payment_transaction->id,                       
                    ]
                    ]); 
        
        
       
        
        $code = $checkstatus->getStatusCode(); 
        if($code == 200) 
        {               
         
          
          
        // ignore if callback has already updated transaction 
            $payment_transaction->refresh();
            $transaction = $payment_transaction;
            //check status 
            if($transaction->status == 'pending')
            {
                //get new status 
                

                $response_body = json_decode($checkstatus->getBody(),true);
                // log request
                $status_variables = var_export($response_body,true);
                 $status_log = date('Y-m-d H:i:s')." WITH:".$status_variables."";
        //log the request 
        \Storage::disk('local')->append('payment_checkstatus_log.txt',$status_log);
                $new_transaction_status = $response_body['transaction_status'];
                //for success create ticket add to email and sms queue
                if($new_transaction_status == 'failed')
                {
                 // immediate failure 
                    $transaction->status = 'failed';
                    $transaction->payment_gateway_result = $response_body['status_code'];
                    $transaction->save();
                    $notification_type = 'Failed';
                    $notification_message = 'Payment has not been successful!';
                    
                }
                else if($new_transaction_status == 'completed')
                {
                    // create tickets
                    $tickets_bought = array();
                    $paid_main_routes = $transaction->main_routes;
                    $paid_stop_over_routes = $transaction->stop_over_routes;
                    $operator = Operator::find($transaction->transport_operator_id);
                    $pad_length = 6;
                    $pad_char = 0;
                    $str_type = 'd'; // treats input as integer, and outputs as a (signed) decimal number

                    $pad_format = "%{$pad_char}{$pad_length}{$str_type}"; // or "%04d"
                    foreach($paid_main_routes as $departure_id)
                    {
                        
                        $departure_time = RoutesDepartureTime::findOrFail($departure_id); // change to find after tests
                        $booking = new Booking;
                        $ticket_number = $operator->code.date('y').sprintf($pad_format, $booking->getNextId());
                        $booking->routes_departure_time_id = $departure_id;
                        $booking->amount = $departure_time->route->price;
                        $booking->date_paid = date('Y-m-d');
                        $booking->date_of_travel = $transaction->date_of_travel;
                        $booking->time_of_travel = $departure_time->departure_time;
                        $booking->ticket_number = $ticket_number;
                        $booking->user_id = $transaction->user_id;
                        $booking->route_type = 'main_route';
                        $booking->save();

                        $tickets_bought[] = $booking->id;

                    } 
                    foreach($paid_stop_over_routes as $departure_id)
                    {
                        
                        $departure_time = RoutesStopOversDepartureTime::findOrFail($departure_id); // change to find after tests
                        $booking = new Booking;
                        $ticket_number = $operator->code.date('y').sprintf($pad_format, $booking->getNextId());
                        $booking->routes_departure_time_id = $departure_id;
                        $booking->amount = $departure_time->route->price;
                        $booking->date_paid = date('Y-m-d');
                        $booking->date_of_travel = $transaction->date_of_travel;
                        $booking->time_of_travel = $departure_time->departure_time;
                        $booking->ticket_number = $ticket_number;
                        $booking->route_type = 'stop_over_route';
                        $booking->user_id = $transaction->user_id;
                        $booking->save();

                        $tickets_bought[] = $booking->id;

                    }

                    // update transaction status
                    $transaction->status = 'completed';
                    $transaction->save();

                    //send notifications 
                    // 1 Sms 
                    $sms_template = SmsTemplate::where([
                        ['operator_id','=',$operator->id],
                        ['purpose','=','TICKET']
                    ])->first();
                    // 2 email 
                    $email_template = EmailTemplate::where([
                        ['operator_id','=',$operator->id],
                        ['purpose','=','TICKET']
                    ])->first();
                    $search_for = array("{FIRST_NAME}", "{TICKET_NO}", "{DEPARTURE_STATION}","{ARRIVAL_STATION}","{DEPARTURE_TIME}","{DEPARTURE_DATE}","{ARRIVAL_TIME}","{ARRIVAL_DATE}","{AMOUNT}","{DATE_PAID}","{PAYMENT_METHOD}");    
                    foreach($tickets_bought as $ticket_id)
                    {
                        $ticket = Booking::find($ticket_id);
                        $departure_route = ($ticket->route_type == 'main_route') ? $ticket->route_departure_time->load(['route', 'route.start','route.end']) : $ticket->stop_over_route_departure_time->load(['route', 'route.start','route.end']);
                        //dd($departure_route);
                        $replace_with = array($transaction->first_name,$ticket->ticket_number, $departure_route->route->start->name, $departure_route->route->end->name,$departure_route->departure_time,$transaction->date_of_travel,$departure_route->arrival_time,$transaction->date_of_travel,$ticket->amount,$ticket->date_paid,$transaction->payment_method);
                        $sms_text = str_replace($search_for, $replace_with, $sms_template->message ?? '');
                        $email_message = str_replace($search_for, $replace_with, $email_template->message ?? '');
                        if($sms_template)
                        {
                          // send sms 
                          if(strpos($notification_message, 'Payment Error:') !== false)
                            {
                                $notification_message = '';
                            }
                        }                    

                        if($email_template)
                        {
                           //send email 
                           
                           $data = ['message' => $email_message];

                            \Mail::to($transaction->email)->send(new TicketEmail($data));
                            if(strpos($notification_message, 'Payment Error:') !== false)
                            {
                                $notification_message = '';
                            }
                            $notification_type = 'success';
                            $notification_message .= 'An Email has been sent to you with your ticket details!';


                        }
                    }
                    

                    

                }


                
            }
        }
        
    }catch(\Exception $e)
    {
            $error = $e->getMessage();
            $error_log = date('Y-m-d H:i:s')."error: ".$error."";
        \Storage::disk('local')->append('payment_errors_log.txt',$error_log);
    }
 */   
            $notification = array(
                'type' => $notification_type,
                'message' => $notification_message
            ); 
            $transactionId = $payment_transaction->id;

            return view('bustravel::frontend.notification',compact('notification','transactionId'));
                   

          
    }

    public function process_payment_callback(Request $request)
    {
        $url = $request->fullUrl();
        $client_ip = $request->ip();
        $method = $request->method();
        $variables = $request->all();
        $variables_to_string = http_build_query($variables);//implode(":",$variables);
        $log = date('Y-m-d H:i:s')." FROM:".$client_ip." BY:".$method." WITH:".$variables_to_string."";
        //log the request 
        $base_api_url = config('bustravel.payment_gateways.mtn_rw.url'); 
        $variables2 = var_export($request,true);
        $log2 = date('Y-m-d H:i:s')." WITH:".$variables2."";
        \Storage::disk('local')->append('payment_callback_log.txt',$log);
        \Storage::disk('local')->append('payment_callback_2_log.txt',$log2);
        $transaction_reference = $request->transaction_reference_number;
        $transaction = PaymentTransaction::find($transaction_reference);
        if($transaction)
        {
            //check status 
            if($transaction->status == 'pending')
            {
                //get new status 
                $new_transaction_status = $request->transaction_status;
                
                //for success create ticket add to email and sms queue
                if($new_transaction_status == 'completed')
                {
                    // create tickets
                    $tickets_bought = array();
                    $paid_main_routes = $transaction->main_routes;
                    $paid_stop_over_routes = $transaction->stop_over_routes;
                    $operator = Operator::find($transaction->transport_operator_id);
                    $pad_length = 6;
                    $pad_char = 0;
                    $str_type = 'd'; // treats input as integer, and outputs as a (signed) decimal number

                    $pad_format = "%{$pad_char}{$pad_length}{$str_type}"; // or "%04d"
                    foreach($paid_main_routes as $departure_id)
                    {
                        
                        $departure_time = RoutesDepartureTime::findOrFail($departure_id); // change to find after tests
                        $booking = new Booking;
                        $ticket_number = $operator->code.date('y').sprintf($pad_format, $booking->getNextId());
                        $booking->routes_departure_time_id = $departure_id;
                        $booking->amount = $departure_time->route->price;
                        $booking->date_paid = date('Y-m-d');
                        $booking->date_of_travel = $transaction->date_of_travel;
                        $booking->time_of_travel = $departure_time->departure_time;
                        $booking->ticket_number = $ticket_number;
                        $booking->user_id = $transaction->user_id;
                        $booking->route_type = 'main_route';
                        $booking->save();

                        $tickets_bought[] = $booking->id;

                    } 
                    foreach($paid_stop_over_routes as $departure_id)
                    {
                        
                        $departure_time = RoutesStopOversDepartureTime::findOrFail($departure_id); // change to find after tests
                        $booking = new Booking;
                        $ticket_number = $operator->code.date('y').sprintf($pad_format, $booking->getNextId());
                        $booking->routes_departure_time_id = $departure_id;
                        $booking->amount = $departure_time->route->price;
                        $booking->date_paid = date('Y-m-d');
                        $booking->date_of_travel = $transaction->date_of_travel;
                        $booking->time_of_travel = $departure_time->departure_time;
                        $booking->ticket_number = $ticket_number;
                        $booking->route_type = 'stop_over_route';
                        $booking->user_id = $transaction->user_id;
                        $booking->save();

                        $tickets_bought[] = $booking->id;

                    }

                    // update transaction status
                    $transaction->status = 'completed';
                    $transaction->save();

                    //send notifications 
                    // 1 Sms 
                    $sms_template = SmsTemplate::where([
                        ['operator_id','=',$operator->id],
                        ['purpose','=','TICKET']
                    ])->first();
                    // 2 email 
                    $email_template = EmailTemplate::where([
                        ['operator_id','=',$operator->id],
                        ['purpose','=','TICKET']
                    ])->first();
                    $search_for = array("{FIRST_NAME}", "{TICKET_NO}", "{DEPARTURE_STATION}","{ARRIVAL_STATION}","{DEPARTURE_TIME}","{DEPARTURE_DATE}","{ARRIVAL_TIME}","{ARRIVAL_DATE}","{AMOUNT}","{DATE_PAID}","{PAYMENT_METHOD}");    
                    foreach($tickets_bought as $ticket_id)
                    {
                        $ticket = Booking::find($ticket_id);
                        $departure_route = ($ticket->route_type == 'main_route') ? $ticket->route_departure_time->load(['route', 'route.start','route.end']) : $ticket->stop_over_route_departure_time->load(['route', 'route.start','route.end']);
                        //dd($departure_route);
                        $replace_with = array($transaction->first_name,$ticket->ticket_number, $departure_route->route->start->name, $departure_route->route->end->name,$departure_route->departure_time,$transaction->date_of_travel,$departure_route->arrival_time,$transaction->date_of_travel,$ticket->amount,$ticket->date_paid,$transaction->payment_method);
                        $sms_text = str_replace($search_for, $replace_with, $sms_template->message ?? '');
                        $email_message = str_replace($search_for, $replace_with, $email_template->message ?? '');
                        
                        //send credit merchant 
                        //get default payment method of operator 
                        $default_payment_method = OperatorPaymentMethod::where([
                            ['operator_id','=', $operator->id],
                            ['is_default','=', '1'],
                        ])->first();
                        $sms_cost = 5; // must get from config later

                        // remove sms_cost from amount 
                       $merchant_credit = ($transaction->send_sms == 1) ? $transaction->amount - $sms_cost : $transaction->amount;
                        $credit_transaction = new CreditTransaction;
                        $credit_transaction->amount = $merchant_credit;
                        $credit_transaction->transaction_id = $transaction->id;
                        $credit_transaction->status = 'pending';
                        $credit_transaction->payee_reference = $default_payment_method->sp_phone_number;
                        $credit_transaction->save();
                        $request_uri = $base_api_url."/makecreditrequest";
                        try{
                            $client = new \GuzzleHttp\Client(['verify' => false]);
                            $checkstatus = $client->request('POST', $request_uri, [                    
                                    'json'   => [
                                        "token" =>"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE0OTk3",                        
                                        "transaction_account" => $default_payment_method->sp_phone_number,
                                        "transaction_reference_number" => $credit_transaction->id, 
                                        "tranasaction_amount"=>$merchant_credit,
                                        "account_number" => "100023",
                                        "payment_operator" => 1001,                                        
                                        "merchant_account" => $default_payment_method->sp_phone_number,
                                        "transaction_source" => "web",
                                        "transaction_destination" => "web",
                                        "transaction_reason" => "Bus Ticket Payment",
                                        "currency" => "RWF",
                                        "first_name" => $operator->name,
                                        "last_name" => $operator->name
                                    ]
                                    ]); 
                        
                        
                       
                        
                        $code = $checkstatus->getStatusCode(); 
                        if($code == 200) 
                        {
                        }
                        }
                        catch(\Exception $e)
                        {
                            $error_log = date('Y-m-d H:i:s')." error:".$e->getMessage()."";
                            \Storage::disk('local')->append('payment_credit_request_log.txt',$error_log);

                        }
                        
                        if($sms_template)
                        {
                          // send sms 
                        }                    

                        if($email_template)
                        {
                           //send email 
                           
                           $data = ['message' => $email_message];

                            \Mail::to($transaction->email)->send(new TicketEmail($data));

                            $notification_type = 'success';
                            $notification_message .= 'An Email has been sent to you with your ticket details!';


                        }
                    }
                    

                    

                }
                else if($new_transaction_status == 'failed')
                {
                   $transaction->status = 'failed';
                   $transaction->payment_gateway_result = $request->status_code; 
                   $transaction->save(); 
                }
                else {
                    $transaction->status = 'failed';
                    $transaction->payment_gateway_result = $new_transaction_status;
                    $transaction->save();
                }
            }

            event(new TransactionStatusUpdated($transaction));
           
        }

        return response()->json([
            "status_code" => "200"
        ]);
    }

    public function credit_request_callback (Request $request)
    {
        $client_ip = $request->ip();
        $method = $request->method();
        $variables = $request->all();
        $variables_to_string = http_build_query($variables);//implode(":",$variables);
        $log = date('Y-m-d H:i:s')." FROM:".$client_ip." BY:".$method." WITH:".$variables_to_string."";
        \Storage::disk('local')->append('payment_credit_callback_log.txt',$log);
        $transaction_reference = $request->transaction_reference_number;
        $credit_transaction = CreditTransaction::find($transaction_reference);
        if($credit_transaction)
        {
            if($credit_transaction->status == 'pending')
            {
                $new_transaction_status = $request->transaction_status;
                if($new_transaction_status == 'completed')
                {
                    $credit_transaction->status = 'completed';
                    $credit_transaction->save();
                }

                else if($new_transaction_status == 'failed')
                {
                    $credit_transaction->status = 'failed';
                    $credit_transaction->payment_gateway_result = $request->status_code;
                    $credit_transaction->save();
                }
                else {
                    $credit_transaction->status = $new_transaction_status;
                    $credit_transaction->payment_gateway_result = $request->status_code;
                    $credit_transaction->save();
                }
            }
        }
    }
}
