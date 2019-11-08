<?php
namespace glorifiedking\BusTravel\Http\Controllers;
use Illuminate\Routing\Controller;
use glorifiedking\BusTravel\Operator;
use glorifiedking\BusTravel\Bus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use File;
class BusesController extends Controller
{
   //fetching buses route('bustravel.buses')
    public function index()
    {
        $buses =Bus::all();
        return view('bustravel::backend.buses.index',compact('buses'));
    }
    //creating buses form route('bustravel.buses.create')
    public function create()
    {
        $bus_operators = Operator::where('status',1)->orderBy('name','ASC')->get();
        return view('bustravel::backend.buses.create',compact('bus_operators'));
    }
    // saving a new buses in the database  route('bustravel.buses.store')
    public function store(Request $request)
    {
      //validation
      $validation = request()->validate(Bus::$rules);
      //saving to the database
      $bus = new Bus;
      $bus->operator_id = request()->input('operator_id');
      $bus->number_plate = request()->input('number_plate');
      $bus->seating_capacity = request()->input('seating_capacity');
      $bus->description = request()->input('description');
      $bus->status = request()->input('status');
      $bus->save();
      //flash('Operator '.$operator->name .' Successfully Added')->success()->important();
      return redirect()->route('bustravel.buses');
    }
    //Bus Edit form route('bustravel.buses.edit')
    public function edit($id)
    {
       $bus_operators = Operator::where('status',1)->orderBy('name','ASC')->get();
       $bus =Bus::find($id);
       if (is_null($bus))
       {
           return Redirect::route('bustravel.buses');
       }
       return view('bustravel::backend.buses.edit',compact('bus_operators','bus'));
    }
    //Update Operator route('bustravel.operators.upadate')
    public function update($id, Request $request)
    {
      //validation
      $validation = request()->validate(Bus::$rules);
      //saving to the database
      $bus = Bus::find($id);
      $bus->operator_id = request()->input('operator_id');
      $bus->number_plate = request()->input('number_plate');
      $bus->seating_capacity = request()->input('seating_capacity');
      $bus->description = request()->input('description');
      $bus->status = request()->input('status');
      $bus->save();
      //flash('Operator '.$operator->name .' Successfully Added')->success()->important();
      return redirect()->route('bustravel.buses.update',$id);
    }
    //Delete Bus
    public function delete($id)
    {
        $bus=Bus::find($id);
        $name =$bus->number_plate;
        $bus->delete();
        //flash($name.' Successfully Deleted')->warning()->important();
        return Redirect::route('bustravel.buses');
    }
}
