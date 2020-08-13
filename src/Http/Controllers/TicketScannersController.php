<?php

namespace glorifiedking\BusTravel\Http\Controllers;

use glorifiedking\BusTravel\EmailTemplate;
use glorifiedking\BusTravel\SmsTemplate;
use Illuminate\Routing\Controller;
use Auth;
use glorifiedking\BusTravel\Operator;
use glorifiedking\BusTravel\TicketScanner;
use glorifiedking\BusTravel\ToastNotification;
use Illuminate\Http\Request;

class TicketScannersController extends Controller
{
    public function __construct()
    {
        $this->middleware('web');
        $this->middleware('auth');
        $this->middleware('can:View BT Customer Transactions');
    }

    public function index(Request $request)
    {
        
        $operator =  Operator::first();
        $operator_id =  $request->id ?? $operator->id ?? 0;
        $ticket_scanners = TicketScanner::where('operator_id',$operator_id)->get();
        $operators = Operator::all();
        
        return view('bustravel::backend.ticket_scanners.index',compact('operator','ticket_scanners','operators'));
    }

    public function create()
    {
        
        $operators = Operator::all();
        return view('bustravel::backend.ticket_scanners.create',compact('operators'));
    }

    public function edit($id)
    {
        try {
            $ticket_scanner = TicketScanner::findOrFail($id);
            $operators = Operator::all();
            return view('bustravel::backend.ticket_scanners.edit',compact('ticket_scanner','operators'));
        }catch(\Exception $e)
        {
            $error_message = $e->getMessage();
            return back()->with(ToastNotification::toast("Error: $error_message",'Error Editing','error')); 
        }

    }

    public function save(Request $request)
    {
        $request->validate([
            'device_id' => 'required|unique:ticket_scanners',
            'operator_id' => 'required|numeric',
            'active' => 'required|numeric',            
            
        ]);
        $ticket_scanner = new TicketScanner;
        $ticket_scanner->operator_id = $request->operator_id;
        $ticket_scanner->device_id = $request->device_id;
        $ticket_scanner->active = $request->active;
        $ticket_scanner->description = [
            "device_make" => $request->device_make,
            "device_model" => $request->device_model,
            "device_location" => $request->device_location,
            "notes" => $request->notes,
        ];
        $ticket_scanner->save();
        return redirect()->route('bustravel.ticket_scanners')->with(ToastNotification::toast('Device  Successfully created ','Device Saving','success'));
    }
    
    public function toggle_status($id)
    {
        $ticket_scanner = TicketScanner::find($id);
        if($ticket_scanner)
        {
            $current_status = $ticket_scanner->active;
            $ticket_scanner->active = ($current_status == 0) ? 1 : 0;
            $ticket_scanner->save();
            return redirect()->route('bustravel.ticket_scanners')->with(ToastNotification::toast("Device status changed"));
        }
        return redirect()->route('bustravel.ticket_scanners')->with(ToastNotification::toast("Device Not Found","Status Changing","error"));

    }

    public function delete($id)
    {
        try {
            $ticket_scanner = TicketScanner::findOrFail($id);
            $logs = $ticket_scanner->logs;
            if(!$logs->isEmpty())
            {
                $error = \Illuminate\Validation\ValidationException::withMessages([
                    'already_used' => ["The device has already been used, cannot delete"],
                    
                    
                ]);
                throw $error;
            }
            $ticket_scanner->delete();
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
            return back()->with(ToastNotification::toast("Error: $error_message",'Error Editing','error'));
        }

        return redirect()->route('bustravel.ticket_scanners')->with(ToastNotification::toast("Device Deleted","Status Changing","success"));

    }
    public function scan_logs($id)
    {
        try {
            $ticket_scanner = TicketScanner::findOrFail($id);
            $logs = $ticket_scanner->logs;

            return view('bustravel::backend.ticket_scanners.logs',compact('logs'));

        } catch (\Exception $e) {
            $error_message = $e->getMessage();
            return back()->with(ToastNotification::toast("Error: $error_message",'Error Fetching log','error'));
        }

        return view('bustravel::backend.notifications.sms_templates.edit',compact('sms_template'));
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'device_id' => 'required|unique:ticket_scanners,device_id,'.$id,
            'operator_id' => 'required|numeric',
            'active' => 'required|numeric',            
            
        ]);
        try {    
        $ticket_scanner = TicketScanner::findOrfail($id);         
        $ticket_scanner->operator_id = $request->operator_id;
        $ticket_scanner->device_id = $request->device_id;
        $ticket_scanner->active = $request->active;
        $ticket_scanner->description = [
            "device_make" => $request->device_make,
            "device_model" => $request->device_model,
            "device_location" => $request->device_location,
            "notes" => $request->notes,
        ];
        $ticket_scanner->save();
    
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
            return back()->withInput()->with(ToastNotification::toast("Error: $error_message",'Error Saving','error'));
        }

        return redirect()->route('bustravel.ticket_scanners')->with(ToastNotification::toast('Successfully Updated Ticket scanner'));

    }

   

    
   
}