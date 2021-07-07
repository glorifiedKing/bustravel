<?php

namespace glorifiedking\BusTravel\Commands;

use Illuminate\Console\Command;
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

class ClearCreditTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bustravel:clearcredit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check pending payment and credit transactions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    
    public function handle()
    {
        $five_minutes_ago = Carbon::now()->subMinutes(5)->todateTimeString();
        //$this->info("time: ".$five_minutes_ago);
        //get payment transactions that are over five minutes ago and still pending
        $over_due_transactions = CreditTransaction::where([
            ['status','=','pending'],
            ['created_at','<',$five_minutes_ago]
        ])->get();
        $base_api_url = config('bustravel.payment_gateways.mtn_rw.url');
        $request_uri = $base_api_url."/checktransactionstatus";   
        $merchant_account = env('default_merchant_account',"RW002");
        foreach($over_due_transactions as $transaction)
        {            
            
            try
            {
                $client = new \GuzzleHttp\Client(['verify' => false]);
                $checkstatus = $client->request('POST', $request_uri, [                    
                        'json'   => [
                            "token" =>"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE0OTk3",                        
                            "transaction_account" => $transaction->payee_reference,
                            "transaction_reference_number" => "1$transaction->transaction_id",
                            "merchant_account" => $merchant_account,                       
                        ]
                ]); 
                
                $code = $checkstatus->getStatusCode(); 
                
                // ignore this if callback has already updated transaction 
                $transaction = $transaction->refresh();                    
                //check status again  
                if($transaction->status == 'pending')
                {
                    //get new status                   
                    $response_body = json_decode($checkstatus->getBody(),true);
                    // log request
                    $status_variables = var_export($response_body,true);
                    $status_log = date('Y-m-d H:i:s')."transaction_id: 1".$transaction->id." WITH:".$status_variables."";
                    //log the request 
                    \Storage::disk('local')->append('credit_checkstatus_log.txt',$status_log);
                    $new_transaction_status = $response_body['transaction_status'];
                                
                    //for success create ticket add to email and sms queue
                    if($new_transaction_status == 'completed')
                    {
                        $transaction->status = 'completed';
                        $transaction->save();
                    }
                    else if($new_transaction_status == 'failed')
                    {
                        $transaction->status = 'failed';
                        $transaction->payment_gateway_result = $response_body['status_code']; 
                        $transaction->save(); 
                    }
                    else
                    {
                        $transaction->status = $new_transaction_status;
                        $transaction->payment_gateway_result = $new_transaction_status;
                        $transaction->save();
                    }
                }
            }
            catch(\Exception $e)
            {
                $error_log = date('Y-m-d H:i:s')."transaction_id: 1".$transaction->id." error:".$e->getMessage()."";
                \Storage::disk('local')->append('payment_errors_log.txt',$error_log);
            }
            // fail transaction if it is still pending at this moment 
            if($transaction->status == 'pending')
            {
                $transaction->status = 'failed';
                $transaction->save();
            }
            
        }
    
        
    }
}
