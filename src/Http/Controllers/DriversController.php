<?php

namespace glorifiedking\BusTravel\Http\Controllers;

use File;
use glorifiedking\BusTravel\Driver;
use glorifiedking\BusTravel\Operator;
use glorifiedking\BusTravel\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;


class DriversController extends Controller
{
    public function __construct()
    {
        $this->middleware('web');
        $this->middleware('auth');
    }

    //fetching Drivers route('bustravel.drivers')
    public function index()
    {
      //$user =User::find(auth()->user()->id);

      if(auth()->user()->hasAnyRole('BT Administrator'))
        {
          $drivers =Driver::where('operator_id',auth()->user()->operator_id)->get();
        }
      else
        {
           $drivers = Driver::all();
        }


        return view('bustravel::backend.drivers.index', compact('drivers'));
    }

    //creating Driver form route('bustravel.buses.create')
    public function create()
    {
        return view('bustravel::backend.drivers.create');
    }

    // saving a new Driver in the database  route('bustravel.drivers.store')
    public function store(Request $request)
    {
        //validation
        $validation = request()->validate(Driver::$rules);
        if ($request->hasFile('picture')) {
            $path = public_path('drivers');
            // creating logos folder if doesnot exit
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
            }
            $name = Str::lower(request()->input('name'));
            $resultString = str_replace(' ', '', $name);
            $photoname = $resultString.'_'.time().'.'.request()->picture->getClientOriginalExtension();
            request()->picture->move(public_path('drivers'), $photoname);
        }
        //saving to the database
        $driver = new Driver();
        $driver->name = request()->input('name');
        $driver->nin = strtoupper(request()->input('nin'));
        $driver->date_of_birth = request()->input('date_of_birth');
        $driver->driving_permit_no = strtoupper(request()->input('driving_permit_no'));
        $driver->picture = $photoname ?? null;
        $driver->phone_number = request()->input('phone_number');
        $driver->address = request()->input('address');
        $driver->status = request()->input('status');
        $driver->save();
        $alerts = [
        'bustravel-flash'         => true,
        'bustravel-flash-type'    => 'success',
        'bustravel-flash-title'   => 'Driver Saving',
        'bustravel-flash-message' => 'Driver '.$driver->name.' has successfully been saved',
    ];

        return redirect()->route('bustravel.drivers')->with($alerts);
    }

    //Bus Edit form route('bustravel.buses.edit')
    public function edit($id)
    {
        $bus_operators = Operator::where('status', 1)->orderBy('name', 'ASC')->get();
        $driver = Driver::find($id);
        if (is_null($driver)) {
            return Redirect::route('bustravel.drivers');
        }

        return view('bustravel::backend.drivers.edit', compact('bus_operators', 'driver'));
    }

    //Update Driver route('bustravel.drivers.upadate')
    public function update($id, Request $request)
    {
        //validation
        $validation = request()->validate([
        'operator_id'       => 'required',
        'name'              => 'required',
        'nin'               => 'required|unique:drivers,nin,'.$id,
        'date_of_birth'     => 'required',
        'driving_permit_no' => 'required|unique:drivers,driving_permit_no,'.$id,
        'phone_number'      => 'required',
        'address'           => 'required',
      ]);

        if ($request->hasFile('newpicture')) {
            $path = public_path('drivers');
            // creating logos folder if doesnot exit
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
            }
            $name = Str::lower(request()->input('name'));
            $resultString = str_replace(' ', '', $name);
            $photoname = $resultString.'_'.time().'.'.request()->newpicture->getClientOriginalExtension();
            request()->newpicture->move(public_path('drivers'), $photoname);
        }
        //saving to the database
        $driver = Driver::find($id);
        $driver->name = request()->input('name');
        $driver->nin = strtoupper(request()->input('nin'));
        $driver->date_of_birth = request()->input('date_of_birth');
        $driver->driving_permit_no = strtoupper(request()->input('driving_permit_no'));
        $driver->picture = $photoname ?? request()->input('picture');
        $driver->phone_number = request()->input('phone_number');
        $driver->address = request()->input('address');
        $driver->status = request()->input('status');
        $driver->save();
        $alerts = [
        'bustravel-flash'         => true,
        'bustravel-flash-type'    => 'success',
        'bustravel-flash-title'   => 'Driver Updating',
        'bustravel-flash-message' => 'Driver has successfully been updated',
    ];

        return redirect()->route('bustravel.drivers.edit', $id)->with($alerts);
    }

    //Delete Driver
    public function delete($id)
    {
        $driver = Driver::find($id);
        $name = $driver->name;
        $driver->delete();
        $alerts = [
            'bustravel-flash'         => true,
            'bustravel-flash-type'    => 'error',
            'bustravel-flash-title'   => 'Driver Deleting ',
            'bustravel-flash-message' => 'Driver '.$name.' has successfully been Deleted',
        ];

        return Redirect::route('bustravel.drivers')->with($alerts);
    }
}
