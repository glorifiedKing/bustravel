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
        $request_uri = "https://10.33.10.199:8100/mot/mm/debit";
       /* $client = new \GuzzleHttp\Client(['verify' => false]);
        $response = $client->request('POST', $request_uri, [
                    ['cert' => ['/home/sslcertificates/197_243_14_94.crt']],
                    'headers' => [
                        'Content-Type' => 'text/xml'
                    ],
                    'body'   => $xml_body
                    ]);
         dd($response);           
            */
                $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://10.33.10.199:8100/mot/mm/debit");
        curl_setopt($ch, CURLOPT_SSLCERT ,  "/home/sslcertificates/197_243_14_94.crt" );
        curl_setopt($ch, CURLOPT_SSLKEY ,  "/home/sslcertificates/197_243_14_94.pem" );
        curl_setopt($ch, CURLOPT_SSLVERSION, 6);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_TIMEOUT,        15);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST,           true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,    $xml_body);
        $result = curl_exec($ch);

        if (curl_errno($ch) > 0) {
            $result = array('errocurl' => curl_errno($ch), 'msgcurl' => curl_error($ch));
            echo curl_error($ch);
            // $result = false;
        }

        curl_close($ch);
       print_r($result); die();
    }

    //save successfull debits
    public function index()
    {
       return 'ok';
    }

    
}
