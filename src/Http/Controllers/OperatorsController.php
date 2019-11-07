<?php
namespace glorifiedking\BusTravel\Http\Controllers;

use Illuminate\Routing\Controller;
use glorifiedking\BusTravel\Operator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use File;
class OperatorsController extends Controller
{
   //fetching operators route('bustravel.operators')
    public function index()
    {
        $bus_operators =Operator::all();
        return view('bustravel::backend.operators.index',compact('bus_operators'));
    }
    //creating operator form route('bustravel.operators.create')
    public function create()
    {
        return view('bustravel::backend.operators.create');
    }
    // saving a new operator in the database  route('bustravel.operators.store')
    public function store(Request $request)
    {
      //validation
      $validation = request()->validate(Operator::$rules);
         // saving the logo image
      if($request->hasFile('logo'))
      {
           $path = public_path('logos');
           // creating logos folder if doesnot exit
           if(!File::isDirectory($path))
           {
               File::makeDirectory($path, 0777, true, true);
           }
           $name =    Str::lower(request()->input('name'));
           $resultString = str_replace(' ', '', $name);
           $photoname = $resultString.'_'.time().'.'.request()->logo->getClientOriginalExtension();
           request()->logo->move(public_path('logos'), $photoname);
      }
      //saving to the database
      $operator = new Operator;
      $operator->name = request()->input('name');
      $operator->address = request()->input('address');
      $operator->code = request()->input('code');
      $operator->logo = $photoname??NULL;
      $operator->email = request()->input('email');
      $operator->contact_person_name = request()->input('contact_person_name');
      $operator->phone_number = request()->input('phone_number');
      $operator->status = request()->input('status');
      $operator->save();
      //flash('Operator '.$operator->name .' Successfully Added')->success()->important();
      return redirect()->route('bustravel.operators');
    }
    //operator Edit form route('bustravel.operators.edit')
    public function edit($id)
    {
       $bus_operator = Operator::find($id);
       if (is_null($bus_operator))
       {
           return Redirect::route('bustravel.operators');
       }
       return view('bustravel::backend.operators.edit',compact('bus_operator'));
    }
    //Update Operator route('bustravel.operators.upadate')
    public function update($id, Request $request)
    {
      //validation
      $validation = request()->validate(Operator::$rules);
      // saving logo to logos folder
      if($request->hasFile('newlogo'))
      {
           // creating logos folder if doesnot exit
           $path = public_path('logos');
           if(!File::isDirectory($path))
           {
               File::makeDirectory($path, 0777, true, true);
           }
           $name =    Str::lower(request()->input('name'));
           $resultString = str_replace(' ', '', $name);
           $photoname = $resultString.'_'.time().'.'.request()->newlogo->getClientOriginalExtension();
           request()->newlogo->move(public_path('logos'), $photoname);
      }
      //saving to the database
      $operator = Operator::find($id);
      $operator->name = request()->input('name');
      $operator->address = request()->input('address');
      $operator->code = request()->input('code');
      $operator->logo = $photoname??request()->input('logo');
      $operator->email = request()->input('email');
      $operator->contact_person_name = request()->input('contact_person_name');
      $operator->phone_number = request()->input('phone_number');
      $operator->status = request()->input('status');
      $operator->save();
      //flash('Operator '.$operator->name .' Successfully Added')->success()->important();
      return redirect()->route('bustravel.operators.update',$id);
    }
    //Delete Operator
    public function delete($id)
    {
        $operator=Operator::find($id);
        $name =$operator->name;
        $operator->delete();
        //flash($name.' Successfully Deleted')->warning()->important();
        return Redirect::route('bustravel.operators');
    }
}
