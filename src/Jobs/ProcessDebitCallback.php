<?php
namespace glorifiedking\BusTravel\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;
use glorifiedking\BusTravel\PaymentTransaction;
use glorifiedking\BusTravel\Booking;
use glorifiedking\BusTravel\RoutesStopoversDepartureTime;
use glorifiedking\BusTravel\Operator;
use glorifiedking\BusTravel\SmsTemplate;
use glorifiedking\BusTravel\EmailTemplate;
use glorifiedking\BusTravel\OperatorPaymentMethod;
use glorifiedking\BusTravel\CreditTransaction;
use glorifiedking\BusTravel\Events\TransactionStatusUpdated;
use glorifiedking\BusTravel\GeneralSetting;
use glorifiedking\BusTravel\RoutesDepartureTime;
use Barryvdh\DomPDF\Facade\Pdf;
use glorifiedking\BusTravel\Mail\TicketEmail;
use AfricasTalking\SDK\AfricasTalking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ProcessDebitCallback implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $status_code;
    private $transaction_status;
    private $transaction_id;
    private $url;
    private $client_ip;
    private $method;
    private $variables;
    private $flutter_reference;

    public function __construct(Request $request)
    {
        $palm_kash_prefix = env("default_gateway_prefix","");
        $flutter_transaction_prefix = env("FLUTTERWAVE_TRANSACTION_PREFIX","flutter");
        $chars_palm = strlen($palm_kash_prefix);
        $chars_flutter = strlen($flutter_transaction_prefix);
        $this->transaction_id = (isset($request->transaction_reference_number)) ? substr($request->transaction_reference_number,$chars_palm) : substr($request->tx_ref,$chars_flutter);
        $this->transaction_status = (isset($request->transaction_reference_number)) ? $request->transaction_status : $request->status;
        $this->status_code = $request->status_code;
        $this->url = $request->fullUrl();
        $this->client_ip = $request->ip();
        $this->method = $request->method();
        $this->variables = $request->all();
        $this->flutter_reference = $request->transaction_id ?? 0;
    }

    private function generateRandomTicket(string $prefix, int|string $key) : string
    {
        $pKey = strval($key);
        return strtoupper(uniqid($prefix).substr($pKey, strlen($pKey)-1));
        
    }

    public function handle()
    {
        $base_api_url = config('bustravel.payment_gateways.mtn_rw.url'); 
        
        $variables_to_string = http_build_query($this->variables);//implode(":",$variables);
        $log = date('Y-m-d H:i:s')." transaction_id: ".$this->transaction_id." transaction_status ".$this->transaction_status." FROM:".$this->client_ip." BY:".$this->method." WITH:".$variables_to_string."";
        //log the request 
        $base_api_url = config('bustravel.payment_gateways.mtn_rw.url'); 
        \Storage::disk('local')->append('payment_callback_log.txt',$log);
        $transaction = PaymentTransaction::find($this->transaction_id);

        if($transaction)
        {
            //check status 
            if($transaction->status == 'pending')
            {
                //get new status 
                $new_transaction_status = $this->transaction_status;
                
                
                //for success create ticket add to email and sms queue
                if($new_transaction_status == 'completed' || $new_transaction_status == 'successful')
                {
                    // check flutter
                    $flutter_result = "successful";
                    if($this->flutter_reference != 0)
                    {
                        $flutter_reference = $this->flutter_reference;                        
                        $request_uri = env("FLUTTERWAVE_API_URL","http://localhost")."transactions/$flutter_reference/verify";                        
                        $token = env("FLUTTERWAVE_KEY","1234542");                        
                        $headers = [
                            'Authorization' => 'Bearer ' . $token,        
                            'Accept'        => 'application/json',
                        ];
                        $client = new \GuzzleHttp\Client(['headers' => $headers]);
                        $verification_request = $client->request('GET', $request_uri);
                        $response_body = json_decode($verification_request->getBody(),true);
                        $flutter_result = $response_body['data']['status'];

                    }

                    if($flutter_result == "successful")
                    {
                    // create tickets
                        $no_of_tickets = $transaction->no_of_tickets;
                        $tickets_bought = array();
                        $paid_main_routes = $transaction->main_routes;
                        $paid_stop_over_routes = $transaction->stop_over_routes;
                        $operator = Operator::find($transaction->transport_operator_id);
                        $pad_length = 6;
                        $pad_char = 0;
                        $str_type = 'd'; // treats input as integer, and outputs as a (signed) decimal number
                        $send_sms = $transaction->send_sms;
                        $africas_talking_username = GeneralSetting::where('setting_prefix','africas_talking_username')->first()->setting_value ?? 'username';
                        $africas_talking_apikey = GeneralSetting::where('setting_prefix','africas_talking_apikey')->first()->setting_value ?? 'apikey';
                        $pad_format = "%{$pad_char}{$pad_length}{$str_type}"; // or "%04d"
                        foreach($paid_main_routes as $departure_id)
                        {
                            for($i=0;$i<$no_of_tickets;$i++)
                            {
                                $departure_time = RoutesDepartureTime::findOrFail($departure_id); // change to find after tests
                                $booking = new Booking;
                                $ticket_number = $this->generateRandomTicket($operator->code, rand(10,100));
                                $booking->routes_departure_time_id = $departure_id;
                                $booking->amount = $departure_time->route->price;
                                $booking->date_paid = date('Y-m-d');
                                $booking->date_of_travel = $transaction->date_of_travel;
                                $booking->time_of_travel = $departure_time->departure_time;
                                $booking->ticket_number = $ticket_number;
                                $booking->user_id = $transaction->user_id;
                                $booking->route_type = 'main_route';
                                $booking->payment_source = $transaction->payment_source;
                                $booking->payment_transaction_id = $transaction->id;
                                $booking->save();

                                $tickets_bought[] = $booking->id;
                            }
                            

                        } 
                        foreach($paid_stop_over_routes as $departure_id)
                        {
                            for($i=0;$i<$no_of_tickets;$i++)
                            {
                                $departure_time = RoutesStopOversDepartureTime::findOrFail($departure_id); // change to find after tests
                                $booking = new Booking;
                                $ticket_number = $this->generateRandomTicket($operator->code, rand(10,100));
                                $booking->routes_departure_time_id = $departure_id;
                                $booking->amount = $departure_time->route->price;
                                $booking->date_paid = date('Y-m-d');
                                $booking->date_of_travel = $transaction->date_of_travel;
                                $booking->time_of_travel = $departure_time->departure_time;
                                $booking->ticket_number = $ticket_number;
                                $booking->route_type = 'stop_over_route';
                                $booking->user_id = $transaction->user_id;
                                $booking->payment_source = $transaction->payment_source;
                                $booking->payment_transaction_id = $transaction->id;
                                $booking->save();

                                $tickets_bought[] = $booking->id;
                            }

                            

                        }

                        // update transaction status
                        $transaction->status = 'completed';
                        $transaction->save();

                        //send notifications 
                        // 1 Sms 
                        $sms_template = SmsTemplate::where([
                            ['operator_id','=',$operator->id],
                            ['purpose','=','TICKET'],
                            ['language','=',$transaction->language]
                        ])->first();
                        // 2 email 
                        $email_template = EmailTemplate::where([
                            ['operator_id','=',$operator->id],
                            ['purpose','=','TICKET'],
                            ['language','=',$transaction->language]
                        ])->first();
                        $search_for = array("{FIRST_NAME}", "{TICKET_NO}", "{DEPARTURE_STATION}","{ARRIVAL_STATION}","{DEPARTURE_TIME}","{DEPARTURE_DATE}","{ARRIVAL_TIME}","{ARRIVAL_DATE}","{AMOUNT}","{DATE_PAID}","{PAYMENT_METHOD}");
                        
                        foreach($tickets_bought as $ticket_id)
                        {
                            $ticket = Booking::find($ticket_id);
                            $departure_route = ($ticket->route_type == 'main_route') ? $ticket->route_departure_time->load(['route', 'route.start','route.end']) : $ticket->stop_over_route_departure_time->load(['route', 'route.start','route.end']);
                            //dd($departure_route);
                            $replace_with = array($transaction->first_name,$ticket->ticket_number, $departure_route->route->start->name, $departure_route->route->end->name,$departure_route->departure_time,Carbon::parse($transaction->date_of_travel)->format("d/m/Y"),$departure_route->arrival_time,Carbon::parse($transaction->date_of_travel)->format('d/m/Y'),$ticket->amount,Carbon::parse($ticket->date_paid)->format("d/m/Y"),$transaction->payment_method);

                            $file_name = public_path('/tickets/'.$ticket->ticket_number.".pdf");
                            $first_name = $ticket->payment_transaction->first_name ?? '';
                            $last_name = $ticket->payment_transaction->last_name ?? '';
                            $name = strtoupper("$first_name "."$last_name");
    
                            $ticket_data = [
                                'ticket_number' => $ticket->ticket_number ? $ticket->ticket_number : null,
                                'ticket_price' => $ticket->amount ? $ticket->amount : null,
                                'seat_number' => $ticket->seat_number ? $ticket->seat_number : null,
                                'departure_station' => $departure_route->route->start->name ? $departure_route->route->start->name : null,
                                'arrival_station' => $departure_route->route->end->name ? $departure_route->route->end->name : null,
                                'departure_time' => Carbon::parse($departure_route->departure_time)->format('h:i A'),
                                'destination_time' => Carbon::parse($departure_route->arrival_time)->format('h:i A'),
                                'name' =>  $name,
                                'date_paid' => Carbon::parse($ticket->date_paid)->format('Y-m-d h:i A'),
                                'time_of_payment' => Carbon::parse($ticket->date_paid)->format('h:i A'),
                                'date_of_travel' => Carbon::parse($ticket->date_of_travel)->format('Y-m-d')
                            ];
    
                            $pdf = Pdf::loadView('bustravel::backend.notifications.pdf_ticket', $ticket_data)->setWarnings(false)->save($file_name);

                            $sms_text = str_replace($search_for, $replace_with, $sms_template->message ?? '');
                            $sms_text = $sms_text ." ".url('/tickets/'.$ticket->ticket_number.".pdf");
                            $email_message = str_replace($search_for, $replace_with, $email_template->message ?? '');
                            
                            //send credit merchant 
                            //get default payment method of operator 
                            $default_payment_method = OperatorPaymentMethod::where([
                                ['operator_id','=', $operator->id],
                                ['is_default','=', '1'],
                            ])->first();
                            $sms_cost = GeneralSetting::where('setting_prefix','sms_cost_rw')->first()->setting_value ?? 10;

                            // remove sms_cost from amount 
                        $merchant_credit = ($transaction->send_sms == 1) ? $transaction->amount - $sms_cost : $transaction->amount;
                            $credit_transaction = new CreditTransaction;
                            $credit_transaction->amount = $merchant_credit;
                            $credit_transaction->transaction_id = $transaction->id;
                            $credit_transaction->status = 'pending';
                            $credit_transaction->payee_reference = $default_payment_method->sp_phone_number ?? "RW002";
                            $credit_transaction->save();
                            // we are concaneting 1 to the transaction id to create unique number 1 is for credit requests
                            $make_credit_requests = env('credit_all_transactions',"FALSE");
                            $payment_operator = env('default_payment_operator',"1002");
                            $merchant_account = env('default_merchant_account',"RW002");
                            $credit_request_uri = $base_api_url."/makecreditrequest";
                            if($make_credit_requests == "FALSE")
                            {
                                try{
                                    $client = new \GuzzleHttp\Client(['decode_content' => false,'verify' => false]);
                                    $checkstatus = $client->request('POST', $credit_request_uri, [                    
                                            'json'   => [
                                                "token" =>"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE0OTk3",                        
                                                "transaction_account" => $default_payment_method->sp_phone_number ?? "RW002",
                                                "transaction_reference_number" => "1$transaction->id", 
                                                "transaction_amount"=>$merchant_credit,
                                                "account_number" => "100023",
                                                "payment_operator" => $payment_operator,                                        
                                                "merchant_account" => $merchant_account,
                                                "transaction_source" => "web",
                                                "transaction_destination" => "web",
                                                "transaction_reason" => "Bus Ticket Payment",
                                                "currency" => "RWF",
                                                "first_name" => $operator->name,
                                                "last_name" => $operator->name
                                            ]
                                            ]); 
                                
                                
                            
                                
                                $code = $checkstatus->getStatusCode(); 
                                $request_log = date('Y-m-d H:i:s')." code:".$code."";
                                \Storage::disk('local')->append('payment_credit_request_log.txt',$request_log);    
                                if($code == 200) 
                                {
                                    $response_body = json_decode($checkstatus->getBody(),true);
                                    // log request
                                    $status_variables = var_export($response_body,true);
                                    $status_log = date('Y-m-d H:i:s')." transaction_id: 1".$transaction->id." WITH:".$status_variables."";
                                    //save the request 
                                    \Storage::disk('local')->append('payment_credit_request_log.txt',$status_log);
                                    $new_transaction_status = $response_body['transaction_status'];
                                    //for failed 
                                    if($new_transaction_status == 'failed')
                                    {
                                    // immediate failure 
                                        $credit_transaction->status = 'failed';
                                        $credit_transaction->payment_gateway_result = $response_body['status_code'];
                                        $credit_transaction->save();                                                               

                                    }
                                }
                                }
                                catch(\Exception $e)
                                {
                                    $error_log = date('Y-m-d H:i:s')." transaction_id: 1".$transaction->id." err:".$e->getMessage()."";
                                    \Storage::disk('local')->append('payment_credit_request_log.txt',$error_log);

                                }
                            }
                            
                            if($sms_template && $send_sms == 1)
                            {
                            // send sms 
                                try{
                                    $from = "PalmKash";
                                    $AT       = new AfricasTalking($africas_talking_username, $africas_talking_apikey);                                
                                    $sms      = $AT->sms();                                
                                    $result   = $sms->send([
                                        "to"      => $transaction->phone_number,
                                        "message" => $sms_text,
                                        "from" => $from,
                                    ]);
                                    $sms_log = date('Y-m-d H:i:s')." transaction_id: 1: ".$transaction->id." sms status:".$result['status']."";
                                    \Storage::disk('local')->append('sms_log.txt',$sms_log);

                                }
                                catch(\Exception $e)
                                {
                                    $error_log = date('Y-m-d H:i:s')." sms_error transaction_id: 1: ".$transaction->id." Error:".$e->getMessage()."";
                                    \Storage::disk('local')->append('sms_error_log.txt',$error_log);

                                }
                            }                    

                            if($email_template)
                            {
                                
                            
                                try {
                                $data = ['message' => $email_message];

                                \Mail::to($transaction->email)->send(new TicketEmail($data));
                                }
                                catch(\Exception $e)
                                {
                                    $error_log = date('Y-m-d H:i:s')." email_error transaction_id: 1: ".$transaction->id." error:".$e->getMessage()."";
                                    \Storage::disk('local')->append('email_error_log.txt',$error_log); 
                                }
                            


                            }
                        }

                    }
                    

                    

                }
                else if($new_transaction_status == 'failed')
                {
                   $transaction->status = 'failed';
                   $transaction->payment_gateway_result = $this->status_code; 
                   $transaction->save(); 
                   //wait(10); 
                }
                else {
                    $transaction->status = $new_transaction_status;
                    $transaction->payment_gateway_result = $new_transaction_status;
                    $transaction->save();
                    //wait(10);
                }
            }

            //$transaction = $transaction->refresh();
            event(new TransactionStatusUpdated($transaction));
        }
            
    }

}
