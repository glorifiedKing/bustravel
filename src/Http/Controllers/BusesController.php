<?php

namespace glorifiedking\BusTravel\Http\Controllers;

use glorifiedking\BusTravel\Bus;
use glorifiedking\BusTravel\Operator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;

class BusesController extends Controller
{
    public function __construct()
    {
        $this->middleware('web');
        $this->middleware('auth');
    }

    //fetching buses route('bustravel.buses')
    public function index()
    {
        if (!auth()->user()->can('View BT Buses')) {
            return redirect()->route('bustravel.errors.403');
        }

        if(auth()->user()->hasAnyRole('BT Administrator'))
          {
            $buses =Bus::where('operator_id',auth()->user()->operator_id)->get();
          }
        else
          {
           $buses = Bus::all();
          }


        return view('bustravel::backend.buses.index', compact('buses'));
    }

    //creating buses form route('bustravel.buses.create')
    public function create()
    {
        return view('bustravel::backend.buses.create');
    }

    // saving a new buses in the database  route('bustravel.buses.store')
    public function store(Request $request)
    {
        //validation
        $validation = request()->validate(Bus::$rules);
        //saving to the database
        $bus = new Bus();
        $bus->number_plate = request()->input('number_plate');
        $bus->seating_capacity = request()->input('seating_capacity');
        $bus->description = request()->input('description');
        $bus->status = request()->input('status');
        $bus->save();
        $alerts = [
        'bustravel-flash'         => true,
        'bustravel-flash-type'    => 'success',
        'bustravel-flash-title'   => 'Bus Saving',
        'bustravel-flash-message' => 'Bus has successfully been saved',
    ];

        return redirect()->route('bustravel.buses')->with($alerts);
    }

    //Bus Edit form route('bustravel.buses.edit')
    public function edit($id)
    {
        $bus_operators = Operator::where('status', 1)->orderBy('name', 'ASC')->get();
        $bus = Bus::find($id);
        if (is_null($bus)) {
            return Redirect::route('bustravel.buses');
        }

        return view('bustravel::backend.buses.edit', compact('bus_operators', 'bus'));
    }

    //Update Operator route('bustravel.operators.upadate')
    public function update($id, Request $request)
    {
        //validation
        $validation = request()->validate([
        'number_plate'     => 'required|unique:buses,number_plate,'.$id,
        'seating_capacity' => 'required|integer',
      ]);
        //saving to the database
        $bus = Bus::find($id);
        $bus->number_plate = request()->input('number_plate');
        $bus->seating_capacity = request()->input('seating_capacity');
        $bus->description = request()->input('description');
        $bus->status = request()->input('status');
        $bus->save();
        $alerts = [
        'bustravel-flash'         => true,
        'bustravel-flash-type'    => 'success',
        'bustravel-flash-title'   => 'Bus Updating',
        'bustravel-flash-message' => 'Bus has successfully been updated',
    ];

        return redirect()->route('bustravel.buses.edit', $id)->with($alerts);
    }

    //Delete Bus
    public function delete($id)
    {
        $bus = Bus::find($id);
        
        $bus->delete();
        $alerts = [
            'bustravel-flash'         => true,
            'bustravel-flash-type'    => 'error',
            'bustravel-flash-title'   => 'Bus Deleting ',
            'bustravel-flash-message' => 'Bus has successfully been Deleted',
        ];

        return Redirect::route('bustravel.buses')->with($alerts);
    }
}
