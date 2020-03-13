<?php

namespace glorifiedking\BusTravel\Http\Controllers;
use glorifiedking\BusTravel\Faq;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;

class FaqsController extends Controller
{
    public function __construct()
    {
        $this->middleware('web');
        $this->middleware('auth');
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
        $alerts = [
        'bustravel-flash'         => true,
        'bustravel-flash-type'    => 'success',
        'bustravel-flash-title'   => 'Faq Saving',
        'bustravel-flash-message' => 'Faq has successfully been saved',
    ];

        return redirect()->route('bustravel.faqs')->with($alerts);
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
        $alerts = [
        'bustravel-flash'         => true,
        'bustravel-flash-type'    => 'success',
        'bustravel-flash-title'   => 'Faq Updating',
        'bustravel-flash-message' => 'Faq has successfully been Updated',
    ];

        return redirect()->route('bustravel.faqs')->with($alerts);
    }

    //Delete Field
    public function deletefaqs($id)
    {
        $faq = Faq::find($id);
        $name = $faq->question;
        $faq->delete();
        $alerts = [
            'bustravel-flash'         => true,
            'bustravel-flash-type'    => 'error',
            'bustravel-flash-title'   => 'Faq Deleted',
            'bustravel-flash-message' =>  $name." has successfully been deleted",
        ];

        return Redirect::route('bustravel.faqs')->with($alerts);
    }
}
