<?php

namespace glorifiedking\BusTravel\Http\Controllers;

use glorifiedking\BusTravel\EmailTemplate;
use glorifiedking\BusTravel\SmsTemplate;
use Illuminate\Routing\Controller;
use Auth;
use Carbon\Carbon;
use glorifiedking\BusTravel\CreditTransaction;
use glorifiedking\BusTravel\ToastNotification;
use Illuminate\Http\Request;

class PaymentReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('web');
        $this->middleware('auth');
        $this->middleware('can:View BT Payment Reports');
    }

    public function list($start_month = 'last_month')
    {
        // get for last month by default 
        $period = ($start_month == 'last_month') ? Carbon::now()->subMonth()->toDateTimeString() : $start_month;
        $operator_id = Auth::user()->operator_id ?? 0;
        $payment_reports = CreditTransaction::with(['payment_transaction' => function ($query) use($operator_id) {
            $query->where('transport_operator_id', '=', $operator_id);
        }])->where([
            ['created_at', '>=', $period],
                      
        ])->get();
        $count_all_payments = CreditTransaction::count();
        return view('bustravel::backend.reports.payments.list',compact('payment_reports','count_all_payments','period'));
    }


}