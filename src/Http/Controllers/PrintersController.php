<?php

namespace glorifiedking\BusTravel\Http\Controllers;

use glorifiedking\BusTravel\Printer;
use glorifiedking\BusTravel\SmsTemplate;
use Illuminate\Routing\Controller;
use Auth;
use glorifiedking\BusTravel\ToastNotification;
use Illuminate\Http\Request;
class PrintersController extends Controller
{
    public function __construct()
    {
        $this->middleware('web');
        $this->middleware('auth');
        $this->middleware('can:Manage BT Operator Settings');
    }

    public function view_printers()
    {

        $operator_id = Auth::user()->operator_id ?? 0;

        $printers = Printer::where('operator_id',$operator_id)->get();
        $printers_latest_count = Printer::where('operator_id',$operator_id)->latest()->count();
        return view('bustravel::backend.printers.list',compact('printers','printers_latest_count'));
    }

    public function create_printer()
    {
        $operator_id = Auth::user()->operator_id ?? 0;
        return view('bustravel::backend.printers.create',compact('operator_id'));
    }

    public function edit_printer($template_id)
    {
        try {
            $printer = Printer::findOrFail($template_id);
        } catch (\Exception $e) {
            $error_message = "Could not find that email template";
            return back()->with(ToastNotification::toast("Error: $error_message",'Error Editing','error'));
        }

        return view('bustravel::backend.printers.edit',compact('printer'));

    }

    public function save_printer(Request $request)
    {
        $validated = $request->validate([
            'printer_name' => 'required',
            'printer_url' => 'required',
            'is_default' => 'required',
            'operator_id' => 'required|gt:base_operator',
            'printer_port' => 'required',
        ]);


        $printer = new Printer;
        $printer->printer_name = $request->printer_name;
        $printer->printer_url = $request->printer_url;
        $printer->is_default = $request->is_default;
        $printer->printer_port = $request->printer_port;
        $printer->operator_id = $request->operator_id;
        try {
            $printer->save();
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
            return back()->withInput()->with(ToastNotification::toast("Error: $error_message",'Error Saving','error'));
        }

        return redirect()->route('bustravel.printers.list')->with(ToastNotification::toast('Successfully saved Printer'));

    }


    public function update_printer(Request $request, $printer_id)
    {
        $validated = $request->validate([
            'printer_name' => 'required',
            'printer_url' => 'required',
            'is_default' => 'required',
            'operator_id' => 'required|gt:base_operator',
            'printer_port' => 'required',
        ]);

        try {
            $printer = Printer::findOrFail($printer_id);
        } catch (\Exception $e) {
            $error_message = "Could not find that email template";
            return back()->with(ToastNotification::toast("Error: $error_message",'Error Editing','error'));
        }

        $printer->printer_name = $request->printer_name;
        $printer->printer_url = $request->printer_url;
        $printer->is_default = $request->is_default;
        $printer->printer_port = $request->printer_port;
        try {
            $printer->save();
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
            return back()->withInput()->with(ToastNotification::toast("Error: $error_message",'Error Saving','error'));
        }

        return redirect()->route('bustravel.printers.list')->with(ToastNotification::toast('Successfully updated Printer'));


    }


    public function delete_printer($printer_id)
    {
        try {
            $printer = Printer::findOrFail($printer_id);
        } catch (\Exception $e) {
            $error_message = "Could not find that printer";
            return back()->with(ToastNotification::toast("Error: $error_message",'Error Deleting','error'));
        }

        $printer->delete();

        return redirect()->route('bustravel.printers.list')->with(ToastNotification::toast('Successfully deleted Printer'));


    }



}
