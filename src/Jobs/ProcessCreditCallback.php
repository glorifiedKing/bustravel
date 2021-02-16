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


class ProcessCreditCallback implements ShouldQueue
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
        $this->transaction_id = substr($request->transaction_reference_number,1);
        $this->transaction_status = $request->transaction_status;
        $this->status_code = $request->status_code;
        $this->url = $request->fullUrl();
        $this->client_ip = $request->ip();
        $this->method = $request->method();
        $this->variables = $request->all();
    }

    public function handle()
    {
        
        $variables_to_string = http_build_query($this->variables);//implode(":",$variables);
        $log = date('Y-m-d H:i:s')." transaction_id: 1".$this->transaction_id." FROM:".$this->client_ip." BY:".$this->method." WITH:".$variables_to_string."";
        \Storage::disk('local')->append('payment_credit_callback_log.txt',$log);
        
        $credit_transaction = CreditTransaction::where('transaction_id',$this->transaction_id)->first();
        if($credit_transaction)
        {
            if($credit_transaction->status == 'pending')
            {
                $new_transaction_status = $this->transaction_status;
                if($new_transaction_status == 'completed')
                {
                    $credit_transaction->status = 'completed';
                    $credit_transaction->save();
                }

                else if($new_transaction_status == 'failed')
                {
                    $credit_transaction->status = 'failed';
                    $credit_transaction->payment_gateway_result = $this->status_code;
                    $credit_transaction->save();
                }
                else {
                    $credit_transaction->status = $new_transaction_status;
                    $credit_transaction->payment_gateway_result = $this->status_code;
                    $credit_transaction->save();
                }
            }
        }
    }
}
