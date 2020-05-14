<?php

namespace glorifiedking\BusTravel\Http\Controllers;

use glorifiedking\BusTravel\EmailTemplate;
use glorifiedking\BusTravel\SmsTemplate;
use Illuminate\Routing\Controller;
use Auth;
use glorifiedking\BusTravel\ToastNotification;
use Illuminate\Http\Request;

class TicketTemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('web');
        $this->middleware('auth');
        $this->middleware('can:Manage BT Operator Settings');
    }

    public function view_email_templates()
    {
        
        $operator_id = Auth::user()->operator_id ?? 0;
        
        $email_templates = EmailTemplate::where('operator_id',$operator_id)->get();
        $email_templates_latest_count = EmailTemplate::where('operator_id',$operator_id)->latest()->count();
        return view('bustravel::backend.notifications.email_templates.list',compact('email_templates','email_templates_latest_count'));
    }

    public function view_sms_templates()
    {
        
        $operator_id = Auth::user()->operator_id ?? 0;
        $sms_templates = SmsTemplate::where('operator_id',$operator_id)->get();
        $sms_templates_latest_count = EmailTemplate::where('operator_id',$operator_id)->latest()->count();
        return view('bustravel::backend.notifications.sms_templates.list',compact('sms_templates','sms_templates_latest_count'));
    }

    public function create_email_template()
    {
        $operator_id = Auth::user()->operator_id ?? 0;
        return view('bustravel::backend.notifications.email_templates.create',compact('operator_id'));
    }

    public function create_sms_template()
    {
        $operator_id = Auth::user()->operator_id ?? 0;
        return view('bustravel::backend.notifications.sms_templates.create',compact('operator_id'));
    }

    public function edit_email_template($template_id)
    {
        try {
            $email_template = EmailTemplate::findOrFail($template_id);
        } catch (\Exception $e) {
            $error_message = "Could not find that email template";
            return back()->with(ToastNotification::toast("Error: $error_message",'Error Editing','error'));
        }

        return view('bustravel::backend.notifications.email_templates.edit',compact('email_template'));

    }
    public function edit_sms_template($template_id)
    {
        try {
            $sms_template = SmsTemplate::findOrFail($template_id);
        } catch (\Exception $e) {
            $error_message = "Could not find that email template";
            return back()->with(ToastNotification::toast("Error: $error_message",'Error Editing','error'));
        }

        return view('bustravel::backend.notifications.sms_templates.edit',compact('sms_template'));
    }

    public function save_email_template(Request $request)
    {
        $validated = $request->validate([
            'purpose' => 'required',
            'language' => 'required',
            'is_default' => 'required',
            'operator_id' => 'required|gt:base_operator',
            'message' => 'required',
        ]);

                 
        $email_template = new EmailTemplate;
        $email_template->purpose = $request->purpose;
        $email_template->language = $request->language;
        $email_template->is_default = $request->is_default;
        $email_template->message = $request->message;
        $email_template->operator_id = $request->operator_id;
        try {
            $email_template->save();
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
            return back()->withInput()->with(ToastNotification::toast("Error: $error_message",'Error Saving','error'));
        }

        return redirect()->route('bustravel.email.templates')->with(ToastNotification::toast('Successfully saved Email Template'));

    }

    public function save_sms_template(Request $request)
    {
        $validated = $request->validate([
            'purpose' => 'required',
            'language' => 'required',
            'is_default' => 'required',
            'operator_id' => 'required|gt:base_operator',
            'message' => 'required',
        ]);

                 
        $sms_template = new SmsTemplate;
        $sms_template->purpose = $request->purpose;
        $sms_template->language = $request->language;
        $sms_template->is_default = $request->is_default;
        $sms_template->message = $request->message;
        $sms_template->operator_id = $request->operator_id;
        try {
            $sms_template->save();
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
            return back()->withInput()->with(ToastNotification::toast("Error: $error_message",'Error Saving','error'));
        }

        return redirect()->route('bustravel.sms.templates')->with(ToastNotification::toast('Successfully saved Sms Template'));


    }

    public function update_email_template(Request $request, $template_id)
    {
        $validated = $request->validate([
            'purpose' => 'required',
            'language' => 'required',
            'is_default' => 'required',
            'operator_id' => 'required|gt:base_operator',
            'message' => 'required',
        ]);

        try {
            $email_template = EmailTemplate::findOrFail($template_id);
        } catch (\Exception $e) {
            $error_message = "Could not find that email template";
            return back()->with(ToastNotification::toast("Error: $error_message",'Error Editing','error'));
        }
         
        $email_template->purpose = $request->purpose;
        $email_template->language = $request->language;
        $email_template->is_default = $request->is_default;
        $email_template->message = $request->message;
        try {
            $email_template->save();
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
            return back()->withInput()->with(ToastNotification::toast("Error: $error_message",'Error Saving','error'));
        }

        return redirect()->route('bustravel.email.templates')->with(ToastNotification::toast('Successfully updated Email Template'));


    }

    public function update_sms_template(Request $request,$template_id )
    {
        $validated = $request->validate([
            'purpose' => 'required',
            'language' => 'required',
            'is_default' => 'required',
            'operator_id' => 'required|gt:base_operator',
            'message' => 'required',
        ]);

        try {
            $sms_template = SmsTemplate::findOrFail($template_id);
        } catch (\Exception $e) {
            $error_message = "Could not find that email template";
            return back()->with(ToastNotification::toast("Error: $error_message",'Error Editing','error'));
        }
         
        $sms_template->purpose = $request->purpose;
        $sms_template->language = $request->language;
        $sms_template->is_default = $request->is_default;
        $sms_template->message = $request->message;
        try {
            $sms_template->save();
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
            return back()->withInput()->with(ToastNotification::toast("Error: $error_message",'Error Saving','error'));
        }

        return redirect()->route('bustravel.sms.templates')->with(ToastNotification::toast('Successfully updated Sms Template'));

    }

    public function delete_email_template($template_id)
    {
        try {
            $email_template = EmailTemplate::findOrFail($template_id);
        } catch (\Exception $e) {
            $error_message = "Could not find that email template";
            return back()->with(ToastNotification::toast("Error: $error_message",'Error Editing','error'));
        }

        $email_template->delete();

        return redirect()->route('bustravel.email.templates')->with(ToastNotification::toast('Successfully deleted Email Template'));


    }

    public function delete_sms_template($template_id)
    {
        try {
            $email_template = SmsTemplate::findOrFail($template_id);
        } catch (\Exception $e) {
            $error_message = "Could not find that email template";
            return back()->with(ToastNotification::toast("Error: $error_message",'Error Editing','error'));
        }

        $email_template->delete();

        return redirect()->route('bustravel.sms.templates')->with(ToastNotification::toast('Successfully deleted Sms Template'));

    }
   
}