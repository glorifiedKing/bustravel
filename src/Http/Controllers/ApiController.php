<?php

namespace glorifiedking\BusTravel\Http\Controllers;

use glorifiedking\BusTravel\Booking;
use glorifiedking\BusTravel\Bus;
use glorifiedking\BusTravel\DeviceScanLog;
use glorifiedking\BusTravel\Operator;
use glorifiedking\BusTravel\Station;
use glorifiedking\BusTravel\StopoverStation;
use glorifiedking\BusTravel\Route;
use glorifiedking\BusTravel\RoutesDepartureTime;
use glorifiedking\BusTravel\PaymentTransaction;
use glorifiedking\BusTravel\OperatorPaymentMethod;
use glorifiedking\BusTravel\RoutesStopoversDepartureTime;
use glorifiedking\BusTravel\Events\TransactionStatusUpdated;
use glorifiedking\BusTravel\GeneralSetting;
use glorifiedking\BusTravel\TicketScanner;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;

class ApiController extends Controller
{
    public function __construct()
    {
        //$this->middleware('web');
        //$this->middleware('auth');
        $this->middleware('bt_key')->except('index','show_debit_test_form','get_station_by_name','get_route_times','ticket_scan');
    }

    public function show_debit_test_form()
    {
        return view('bustravel::backend.api_code.test_form');
    }
    

    //save successfull debits
    public function index()
    {
       return 'ok';
    }

    public function get_station_by_name($station_name)
    {
        $results = array();
        $stations = Station::where([
            ['name','like',"$station_name%"]
        ])->take(8)->get();
        foreach($stations as $station)
        {
            $result_array = array(
                'id' => $station->id,
                'station' => $station->name
            );
            $results[] = $result_array;
        }
        return $results;
    }

    public function get_route_times($from,$to,$time)
    {
        $results = array();
        $travel_day_of_week = date('l');
        $travel_time = date('H:i');
        $route_results = Route::with(['departure_times' => function ($query) use($travel_day_of_week,$travel_time) {
            $query->where('days_of_week', 'like', "%$travel_day_of_week%")->whereTime('departure_time','>',$travel_time);
        }])->where([
            ['start_station', '=', $from],
            ['end_station', '=', $to],            
        ])->get();
       
        foreach($route_results as $key=> $route)
        {
            foreach($route->departure_times as $d_time)
            {
                // check if route is full
                $seats_left = $d_time->number_of_seats_left(date('Y-m-d'));
                if($seats_left > 0)
                { 
                    $result_array = array(
                        'id' => $d_time->id,
                        'price' => $route->price,
                        'time' => $d_time->departure_time,
                        'route_type' => 'main_route',
                        'operator' => $route->operator->name
                    ); 
                    $results[] = $result_array;
                }
            }
        }

        $stop_over_routes = StopoverStation::with(['departure_times' => function ($query) use($travel_day_of_week,$travel_time) {
            
            $query->whereHas('main_route_departure_time', function ($query) use($travel_day_of_week) {
                $query->where('days_of_week', 'like', "%$travel_day_of_week%");
            });
            $query->whereTime('arrival_time','>',$travel_time);
        },
        
        ])->where([
            ['start_station', '=', $from],
            ['end_station', '=', $to],            
        ])->get();
        foreach($stop_over_routes as $key=> $route)
        {
            foreach($route->departure_times as $d_time)
            {
                $seats_left =$d_time->main_route_departure_time->number_of_seats_left(date('Y-m-d'));
                if($seats_left > 0)
                {
                    $result_array = array(
                        'id' => $d_time->id,
                        'price' => $route->price,
                        'time' => $d_time->departure_time,
                        'route_type' => 'stop_over_route',
                        'operator' => $route->route->operator->name
                    ); 
                    $results[] = $result_array;
                }
            }
        }    
        
        return $results;
    }

    public function ussd(Request $request)
    {
        $variables_to_string = http_build_query($request->all());
        $log = date('Y-m-d H:i:s')." FROM:".$request->ip()." BY:".$request->method()." WITH:".$variables_to_string."";
        //log the request 
        
        \Storage::disk('local')->append('ussd_log.txt',$log);
        $method = $request->input('request_method');

        if($method == 'GetStartStationsByName')
        {
            $station = $request->input('departure_station');
            //validate 
            if(!$station)
            {
                return response()->json([
                    'status' => 'invalid data',
                    'result' => 'departure_station missing'
                ]);
            }
            $result = $this->get_station_by_name($station);
            $status = empty($result) ? 'failed' : 'success';
            return response()->json([
                'status' => $status,
                'result' => $result
            ]);
            
        }
        else if($method == 'GetEndStationsByName')
        {
            $station = $request->input('destination_station');
            $from_station_id = $request->input('from_station_id') ?? 0;
            //validate 
            if(!$station)
            {
                return response()->json([
                    'status' => 'invalid data',
                    'result' => 'destination_station missing'
                ]);
            }
            $result = $this->get_station_by_name($station);
            // remove duplicate from station id 
           
            foreach($result as $key=> $r)
            {
                
                if($r['id'] == $from_station_id)
                {
                    
                    unset($result[$key]);
                }
            }
            $status = empty($result) ? 'failed' : 'success';
            return response()->json([
                'status' => $status,
                'result' => $result
            ]);
        }
        else if($method == 'GetRouteTimes')
        {
            $from_station_id = $request->input('from_station_id');
            $to_station_id = $request->input('to_station_id');
            $time_range = date('Y-m-d');
            // validate
            if(!$from_station_id)
            {
                return response()->json([
                    'status' => 'invalid data',
                    'result' => 'from_station_id missing'
                ]); 
            }
            if(!$to_station_id)
            {
                return response()->json([
                    'status' => 'invalid data',
                    'result' => 'to_station_id missing'
                ]); 
            }
            //get routes 
            $result = $this->get_route_times($from_station_id,$to_station_id,$time_range);
            $status = empty($result) ? 'failed' : 'success';
            return response()->json([
                'status' => $status,
                'result' => $result
            ]);

        }
        else if($method == 'MakeBooking')
        {
            $route_id = $request->input('route_id');
            $route_type = $request->input('route_type');
            $sent_amount = $request->input('amount');
            $no_of_tickets = $request->input('number_of_tickets') ?? 1;
            $first_name = $request->input('name') ?? 'Guest';
            $payee_reference = $request->input('msisdn');
            $date_of_travel = $request->input('date_of_travel') ?? date('Y-m-d');
            $language = $request->input('language') ?? 'kinyarwanda';

            

            // validate
            if(!$route_id)
            {
                return response()->json([
                    'status' => 'invalid data',
                    'result' => 'route_id missing'
                ]); 
            }
            if(!$route_type)
            {
                return response()->json([
                    'status' => 'invalid data',
                    'result' => 'route_type missing'
                ]); 
            }
            if(!$sent_amount)
            {
                return response()->json([
                    'status' => 'invalid data',
                    'result' => 'amount missing'
                ]); 
            }

            //get route details 
            $route = ($route_type == 'main_route') ? RoutesDepartureTime::find($route_id) : RoutesStopoversDepartureTime::find($route_id);
            
            if(!$route)
            {
                return response()->json([
                    'status' => 'failed',
                    'result' => 'the route doesnot exist'
                ]);
            }
            $main_routes = ($route_type == 'main_route') ? [$route_id] : [];
            $stop_over_routes = ($route_type == 'stop_over_route') ? [$route_id] : [];
            $amount = $route->route->price;
            $sms_cost = GeneralSetting::where('setting_prefix','sms_cost_rw')->first()->setting_value ?? 10;
            $amount += $sms_cost;
            $paying_user = 0;

            $operator_id = $route->route->operator->id ?? $route->route->route->operator->id;
            
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
            $payment_transaction->first_name = $first_name;           
            $payment_transaction->phone_number = $payee_reference;           
            $payment_transaction->country = 'RW';
            $payment_transaction->send_sms = 1;
            $payment_transaction->send_email = 0;
            $payment_transaction->date_of_travel = $date_of_travel;
            $payment_transaction->transport_operator_id = $operator_id;
            $payment_transaction->no_of_tickets = $no_of_tickets;
            $payment_transaction->payment_source = 'ussd';
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

            // make booking 
            return response()->json([
                'status' => 'success',
                'result' => 'Waiting for Payment'
            ]);
        }

        return response()->json([
            'status' => 'invalid data',
            'result' => 'unknown method or method not specified'
        ]);
    }

    public function ticket_scan(Request $request)
    {
        if(!isset($request->device_id))
        {
            return response()->json([
                'status_code' => 401,
                'message' => 'Unauthorized'
            ],401);
        }
        $device = TicketScanner::where('device_id',$request->device_id)->first();
        if(!$device)
        {
            return response()->json([
                'status_code' => 401,
                'message' => 'Unauthorized'
            ],401); 
        }
        if($device->active == 0) {
            return response()->json([
                'status_code' => 403,
                'message' => 'Forbidden Device Not Active'
            ],403); 
        }

        if(!isset($request->ticket_number))
        {
            return response()->json([
                'status_code' => 422,
                'message' => 'Missing ticket_number'
            ],422); 
        }

        // search for tickets 
        $ticket = Booking::where('ticket_number',$request->ticket_number)->first();
        if(!$ticket)
        {
            $log = new DeviceScanLog;
            $log->device_id = $device->id;
            $log->ticket_number = $request->ticket_number;
            $log->result = "Ticket Not Found";
            $log->ip_address = $request->ip();
            $log->request_attributes = $request->all();
            $log->save();
            return response()->json([
                'status_code' => 404,
                'message' => 'Ticket Not Found'
            ],404); 
        }
        if($ticket->boarded == 1)
        {
            
            $log = new DeviceScanLog;
            $log->device_id = $device->id;
            $log->ticket_number = $request->ticket_number;
            $log->result = "Ticket Already Used";
            $log->ip_address = $request->ip();
            $log->request_attributes = $request->all();
            $log->save();
            return response()->json([
                'status_code' => 404,
                'message' => 'Ticket Already Used'
            ],404); 
        }

        // check if date of ticket is still on 
        $now = Carbon::now();
        $ticket_travel_time = Carbon::parse($ticket->date_of_travel." ".$ticket->time_of_travel);
        if($now > $ticket_travel_time)
        {
            $log = new DeviceScanLog;
            $log->device_id = $device->id;
            $log->ticket_number = $request->ticket_number;
            $log->result = "Ticket Time Passed";
            $log->ip_address = $request->ip();
            $log->request_attributes = $request->all();
            $log->save();
            return response()->json([
                'status_code' => 404,
                'message' => 'Ticket Time Passed'
            ],404);
        }

        $ticket->boarded = 1;
        $ticket->save();
        $log = new DeviceScanLog;
        $log->device_id = $device->id;
        $log->ticket_number = $request->ticket_number;
        $log->result = "Ticket Boarded";
        $log->ip_address = $request->ip();
        $log->request_attributes = $request->all();
        $log->save();
        return response()->json([
            'status_code' => 200,
            'message' => 'Ticket Boarded'
        ],200);


    }

    
}
