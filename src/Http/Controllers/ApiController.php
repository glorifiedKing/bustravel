<?php

namespace glorifiedking\BusTravel\Http\Controllers;

use glorifiedking\BusTravel\Bus;
use glorifiedking\BusTravel\Operator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;

class ApiController extends Controller
{
    public function __construct()
    {
        //$this->middleware('web');
        //$this->middleware('auth');
    }

    public function show_debit_test_form()
    {
        return view('bustravel::backend.api_code.test_form');
    }

    public function send_debit_request(Request $request)
    {
        $to = $request->to;
        $from = $request->from;
        $amount = $request->amount;
        if(!isset($to) || !isset($from) || !isset($amount))
        {
            return response()->json([
                'error' => 'Invalid Data',
                'message' => 'All fields are required'
            ]);
        }
        $ref_id = rand();
        $external_transaction_id = uniqid();
        //send request to mtn url 
        $xml_body = "<?xml version='1.0' encoding='UTF-8' standalone='yes'?>
            <ns2:debitrequest xmlns:ns2='http://www.ericsson.com/em/emm'>
            <fromfri>FRI:".$from."/MSISDN</fromfri>
            <tofri>FRI:".$to."@pascal.sp/SP</tofri>
            <amount>
            <amount>".$amount."</amount>
            <currency>FRW</currency>
            </amount>
            <externaltransactionid>".$external_transaction_id."</externaltransactionid>
            <tomessage>".$from."</tomessage>
            <referenceid>".$ref_id."</referenceid>
            </ns2:debitrequest>";
        $request_uri = "https://10.33.1.14";
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', $request_uri, [
                    'headers' => [
                        'Content-Type' => 'text/xml'
                    ],
                    'body'   => $xml_body
                    ]);
         dd($response);           

    }

    //save successfull debits
    public function index()
    {
       return 'ok';
    }

    
}