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
use glorifiedking\BusTravel\RoutesDepartureTime;
use glorifiedking\BusTravel\Mail\TicketEmail;

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

    public function __construct(Request $request)
    {
        $this->transaction_id = $request->get('transaction_reference_number');
        $this->transaction_status = $request->get('transaction_status');
        $this->status_code = $request->get('status_code');
        $this->url = $request->fullUrl();
        $this->client_ip = $request->ip();
        $this->method = $request->method();
        $this->variables = $request->all();
    }

    public function handle()
    {
        $base_api_url = config('bustravel.payment_gateways.mtn_rw.url'); 
        
        $variables_to_string = http_build_query($this->variables);//implode(":",$variables);
        $log = date('Y-m-d H:i:s')." transaction_id: ".$this->transaction_id." FROM:".$this->client_ip." BY:".$this->method." WITH:".$variables_to_string."";
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
                if($new_transaction_status == 'completed')
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

                    $pad_format = "%{$pad_char}{$pad_length}{$str_type}"; // or "%04d"
                    foreach($paid_main_routes as $departure_id)
                    {
                        for($i=0;$i<$no_of_tickets;$i++)
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
                        

                    } 
                    foreach($paid_stop_over_routes as $departure_id)
                    {
                        for($i=0;$i<$no_of_tickets;$i++)
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
                        // we are concaneting 1 to the transaction id to create unique number 1 is for credit requests
                        $request_uri = $base_api_url."/makecreditrequest";
                        try{
                            $client = new \GuzzleHttp\Client(['decode_content' => false]);
                            $checkstatus = $client->request('POST', $request_uri, [                    
                                    'json'   => [
                                        "token" =>"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE0OTk3",                        
                                        "transaction_account" => $default_payment_method->sp_phone_number,
                                        "transaction_reference_number" => "1$transaction->id", 
                                        "transaction_amount"=>$merchant_credit,
                                        "account_number" => "100023",
                                        "payment_operator" => 1001,                                        
                                        "merchant_account" => "RW002",
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
                            $error_log = date('Y-m-d H:i:s')." transaction_id: 1".$transaction->id." error:".$e->getMessage()."";
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

                           // $notification_type = 'success';
                            //$notification_message .= 'An Email has been sent to you with your ticket details!';


                        }
                    }
                    

                    

                }
                else if($new_transaction_status == 'failed')
                {
                   $transaction->status = 'failed';
                   $transaction->payment_gateway_result = $this->status_code; 
                   $transaction->save(); 
                }
                else {
                    $transaction->status = 'failed';
                    $transaction->payment_gateway_result = $new_transaction_status;
                    $transaction->save();
                }
            }

            $transaction = $transaction->refresh();
            event(new TransactionStatusUpdated($transaction));
        }
            
    }

}
