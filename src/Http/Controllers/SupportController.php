<?php

namespace glorifiedking\BusTravel\Http\Controllers;

use glorifiedking\BusTravel\Bus;
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
use glorifiedking\BusTravel\Jobs\ResendTicket;
use Illuminate\Support\Carbon;
use glorifiedking\BusTravel\ToastNotification;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;

class SupportController extends Controller
{
    public function __construct()
    {
        $this->middleware('web');
        $this->middleware('auth');
        $this->middleware('can:View BT Customer Transactions');
       
    }

    public function show_transactions(Request $request)
    {

        $selected_date = $request->selected_date ?? date('Y-m-d');
        $transaction_id = $request->transaction_id ?? null;
        $phone_number = (isset($request->phone_number)) ? "250".substr($request->phone_number, -9) : null;
        $transactions = [];
        if(is_null($transaction_id) && is_null($phone_number))
        {
            $transactions = PaymentTransaction::whereDate('created_at',$selected_date)->get();
        }
        else if(isset($request->selected_date))
        {
            
            $transactions = PaymentTransaction::whereDate('created_at',$selected_date)->get(); 
        }
        else if(isset($request->phone_number) && isset($request->selected_date))
        {
            $transactions = PaymentTransaction::where('phone_number',$phone_number)->whereDate('created_at',$selected_date)->get();
        }
        else if(isset($request->phone_number))
        {
            
            $transactions = PaymentTransaction::where('phone_number',$phone_number)->get(); 
        }
        else if(isset($request->transaction_id))
        {
            $transactions = PaymentTransaction::where('id',$transaction_id)->get();
        }
        return view('bustravel::backend.reports.payments.customer_payments',compact('transactions','selected_date','phone_number','transaction_id'));
    }

    public function resend_ticket($transaction_id)
    {
        //send email and sms 
        $transaction = PaymentTransaction::find($transaction_id);
        if(!$transaction)
        {
            return redirect()->route('bustravel.reports.transactions')->with(ToastNotification::toast('Transaction doesnt exist','Ticket Resending','error')); 
        }
        ResendTicket::dispatch($transaction_id);
        return redirect()->route('bustravel.reports.transactions')->with(ToastNotification::toast('Ticket Sent','Ticket Resending'));
    }

}