<?php

namespace glorifiedking\BusTravel\Http\Controllers;
use glorifiedking\BusTravel\Faq;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use glorifiedking\BusTravel\ToastNotification;

class FaqsController extends Controller
{
    public function __construct()
    {
        $this->middleware('web');
        $this->middleware('auth');
        $this->middleware('can:Manage BT General Settings');
    }

    //fetching operators route('bustravel.operators')
    public function faqs()
    {
           $faqs = Faq::all();

        return view('bustravel::backend.faqs.faqs', compact('faqs'));
    }

    public function storefaqs(Request $request)
    {
        //validation
        $validation = request()->validate(Faq::$rules);
        //saving to the database
        $faq = new Faq();
        $faq->question =  request()->input('question');
        $faq->answer = request()->input('answer');
        $faq->save();
        return redirect()->route('bustravel.faqs')->with(ToastNotification::toast('Faq has successfully been saved','Faq Saving'));
    }

    public function updatefaqs($id, Request $request)
    {
        //validation
        $validation = request()->validate(Faq::$rules);
        //saving to the database
        $faq_id = request()->input('id');

        $faq = Faq::find($faq_id);
        $faq->question =  request()->input('question');
        $faq->answer = request()->input('answer');
        $faq->save();
        return redirect()->route('bustravel.faqs')->with(ToastNotification::toast('Faq has successfully been updated','Faq Updating'));
    }

    //Delete Field
    public function deletefaqs($id)
    {
        $faq = Faq::find($id);
        $name = $faq->question;
        $faq->delete();
        return Redirect::route('bustravel.faqs')->with(ToastNotification::toast($name. ' has successfully been Deleted','Faq Deleting','error'));
    }
}
