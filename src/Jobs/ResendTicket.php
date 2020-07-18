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
use glorifiedking\BusTravel\Mail\TicketEmail;
use AfricasTalking\SDK\AfricasTalking;
use Carbon\Carbon;

class ResendTicket implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    
    private $transaction_id;
    

    public function __construct($transaction_id)
    {
        $this->transaction_id = $transaction_id;
        
    }

    public function handle()
    {
        $base_api_url = config('bustravel.payment_gateways.mtn_rw.url');        
        
        $transaction = PaymentTransaction::find($this->transaction_id);

        if($transaction->status == 'completed')
        {
            // get bookings
            $send_sms = $transaction->send_sms;
            $africas_talking_username = GeneralSetting::where('setting_prefix','africas_talking_username')->first()->setting_value ?? 'username';
            $africas_talking_apikey = GeneralSetting::where('setting_prefix','africas_talking_apikey')->first()->setting_value ?? 'apikey';
            $sms_template = SmsTemplate::where([
                ['operator_id','=',$transaction->operator->id],
                ['purpose','=','TICKET'],
                ['language','=',$transaction->language]
            ])->first();
            // 2 email 
            $email_template = EmailTemplate::where([
                ['operator_id','=',$transaction->operator->id],
                ['purpose','=','TICKET'],
                ['language','=',$transaction->language]
            ])->first();
            foreach($transaction->bookings() as $ticket)
            {
   
                $departure_route = ($ticket->route_type == 'main_route') ? $ticket->route_departure_time : $ticket->stop_over_route_departure_time;
               // $departure_route = ($ticket->route_type == 'main_route') ? $ticket->route_departure_time->load(['route', 'route.start','route.end']) : [];//$ticket->stop_over_route_departure_time->load(['route', 'route.start','route.end']);
                $search_for = array("{FIRST_NAME}", "{TICKET_NO}", "{DEPARTURE_STATION}","{ARRIVAL_STATION}","{DEPARTURE_TIME}","{DEPARTURE_DATE}","{ARRIVAL_TIME}","{ARRIVAL_DATE}","{AMOUNT}","{DATE_PAID}","{PAYMENT_METHOD}");   
                $replace_with = array($transaction->first_name,$ticket->ticket_number, $departure_route->route->start->name, $departure_route->route->end->name,$departure_route->departure_time,Carbon::parse($transaction->date_of_travel)->format("d/m/Y"),$departure_route->arrival_time,Carbon::parse($transaction->date_of_travel)->format('d/m/Y'),$ticket->amount,Carbon::parse($ticket->date_paid)->format("d/m/Y"),$transaction->payment_method);
                $sms_text = str_replace($search_for, $replace_with, $sms_template->message ?? '');
                $email_message = str_replace($search_for, $replace_with, $email_template->message ?? '');
                
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
                        $error_log = date('Y-m-d H:i:s')." sms_error transaction_id: 1: ".$transaction->id." error:".$e->getMessage()."";
                        \Storage::disk('local')->append('payment_credit_request_log.txt',$error_log);

                    }
                }                    

                if($email_template && $transaction->email)
                {
                    //send email 
                    
                    $data = ['message' => $email_message];

                    \Mail::to($transaction->email)->send(new TicketEmail($data));

                    // $notification_type = 'success';
                    //$notification_message .= 'An Email has been sent to you with your ticket details!';


                }
            }
        
        }
    }
}