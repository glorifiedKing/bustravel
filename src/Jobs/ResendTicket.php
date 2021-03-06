<?php
namespace glorifiedking\BusTravel\Jobs;

use AfricasTalking\SDK\AfricasTalking;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use glorifiedking\BusTravel\PaymentTransaction;
use Illuminate\Foundation\Bus\Dispatchable;
use glorifiedking\BusTravel\SmsTemplate;
use glorifiedking\BusTravel\EmailTemplate;
use glorifiedking\BusTravel\GeneralSetting;
use glorifiedking\BusTravel\Mail\TicketEmail;
use Illuminate\Bus\Queueable;

use Carbon\Carbon;

class ResendTicket implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    const RWANDA_DATE_FORMAT = "d/m/Y";
    
    private $transaction_id;
    

    public function __construct($transaction_id)
    {
        $this->transaction_id = $transaction_id;
        
    }

    public function handle()
    {
               
        
        $transaction = PaymentTransaction::find($this->transaction_id);

        if($transaction->status == 'completed')
        {
            // get bookings
            $send_sms = $transaction->send_sms;
            $africas_talking_username = GeneralSetting::where('setting_prefix','africas_talking_username')->first()->setting_value ?? 'username';
            $africas_talking_apikey = GeneralSetting::where('setting_prefix','africas_talking_apikey')->first()->setting_value ?? 'apikey';
            $operator_sms_template = SmsTemplate::where([
                ['operator_id','=',$transaction->operator->id],
                ['purpose','=','TICKET'],
                ['language','=',$transaction->language]
            ])->first();
            // 2 email 
            $operator_email_template = EmailTemplate::where([
                ['operator_id','=',$transaction->operator->id],
                ['purpose','=','TICKET'],
                ['language','=',$transaction->language]
            ])->first();
            foreach($transaction->bookings() as $ticket)
            {
   
                $departure_route = ($ticket->route_type == 'main_route') ? $ticket->route_departure_time : $ticket->stop_over_route_departure_time;
               
                $search_for = array("{FIRST_NAME}", "{TICKET_NO}", "{DEPARTURE_STATION}","{ARRIVAL_STATION}","{DEPARTURE_TIME}","{DEPARTURE_DATE}","{ARRIVAL_TIME}","{ARRIVAL_DATE}","{AMOUNT}","{DATE_PAID}","{PAYMENT_METHOD}");   
                $replace_with = array($transaction->first_name,$ticket->ticket_number, $departure_route->route->start->name, $departure_route->route->end->name,$departure_route->departure_time,Carbon::parse($transaction->date_of_travel)->format(self::RWANDA_DATE_FORMAT),$departure_route->arrival_time,Carbon::parse($transaction->date_of_travel)->format(self::RWANDA_DATE_FORMAT),$ticket->amount,Carbon::parse($ticket->date_paid)->format(self::RWANDA_DATE_FORMAT),$transaction->payment_method);
                $sms_text = str_replace($search_for, $replace_with, $operator_sms_template->message ?? '');
                $email_message = str_replace($search_for, $replace_with, $operator_email_template->message ?? '');
                
                if($operator_sms_template && $send_sms == 1)
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
                        $error_log = Carbon::now()->toDateTimeString()." sms_error transaction_id: 1: ".$transaction->id." error:".$e->getMessage()."";
                        \Storage::disk('local')->append('payment_credit_request_log.txt',$error_log);

                    }
                }                    

                if($operator_email_template && $transaction->email)
                {
                    //send email 
                    
                    $data = ['message' => $email_message];

                    \Mail::to($transaction->email)->send(new TicketEmail($data));

                   
                }
            }
        
        }
    }
}