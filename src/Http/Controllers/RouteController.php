<?php

namespace glorifiedking\BusTravel\Http\Controllers;

use glorifiedking\BusTravel\Operator;
use glorifiedking\BusTravel\Route;
use glorifiedking\BusTravel\Station;
use glorifiedking\BusTravel\StopoverRoute;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;

class RouteController extends Controller
{
    public function __construct()
    {
        $this->middleware('web');
        $this->middleware('auth');
    }

    //fetching buses route('bustravel.buses')
    public function index()
    {
        $routes = Route::all();

        return view('bustravel::backend.routes.index', compact('routes'));
    }

    //creating buses form route('bustravel.buses.create')
    public function create()
    {
        $bus_operators = Operator::where('status', 1)->orderBy('name', 'ASC')->get();
        $stations = Station::orderBy('name', 'ASC')->get();
        $routes = Route::where('status', 1)->get();

        return view('bustravel::backend.routes.create', compact('bus_operators', 'stations', 'routes'));
    }

    // saving a new buses in the database  route('bustravel.buses.store')
    public function store(Request $request)
    {
        //validation
        $validation = request()->validate(Route::$rules);
        //saving to the database
        if (request()->input('start_station') == request()->input('end_station')) {
            $alerts = [
          'bustravel-flash'         => true,
          'bustravel-flash-type'    => 'error',
          'bustravel-flash-title'   => 'Route Error',
          'bustravel-flash-message' => 'Start Station cannot be the same as End Station',
      ];

            return redirect()->route('bustravel.routes.create')->with($alerts);
        }
        $route = new Route();
        $route->operator_id = request()->input('operator_id');
        $route->start_station = request()->input('start_station');
        $route->end_station = request()->input('end_station');
        $route->price = str_replace(',', '', request()->input('price'));
        $route->return_price = str_replace(',', '', request()->input('return_price'));
        $route->status = request()->input('status');
        $route->save();
        //stop over routes
        $stopovers = request()->input('stopover_id') ?? 0;
        if ($stopovers != 0) {
            $order = request()->input('stopover_order');
            foreach ($stopovers as $index => $stopover_id) {
                $stopover = new StopoverRoute();
                $stopover->route_id = $route->id;
                $stopover->stopover_id = $stopover_id;
                $stopover->order = $order[$index];
                $stopover->save();
            }
        }
        $alerts = [
        'bustravel-flash'         => true,
        'bustravel-flash-type'    => 'success',
        'bustravel-flash-title'   => 'Route Saving',
        'bustravel-flash-message' => 'Route has successfully been saved',
    ];

        return redirect()->route('bustravel.routes')->with($alerts);
    }

    //Bus Edit form route('bustravel.buses.edit')
    public function edit($id)
    {
        $bus_operators = Operator::where('status', 1)->orderBy('name', 'ASC')->get();
        $stations = Station::orderBy('name', 'ASC')->get();
        $routes = Route::where('status', 1)->get();
        $route = Route::find($id);
        if (is_null($route)) {
            return Redirect::route('bustravel.routes');
        }

        return view('bustravel::backend.routes.edit', compact('bus_operators', 'route', 'stations', 'routes'));
    }

    //Update Operator route('bustravel.operators.upadate')
    public function update($id, Request $request)
    {
        //validation
        $validation = request()->validate(Route::$rules);
        if (request()->input('start_station') == request()->input('end_station')) {
            $alerts = [
          'bustravel-flash'         => true,
          'bustravel-flash-type'    => 'error',
          'bustravel-flash-title'   => 'Route Error',
          'bustravel-flash-message' => 'Start Station cannot be the same as End Station',
      ];

            return redirect()->route('bustravel.routes.edit', $id)->with($alerts);
        }
        //saving to the database
        $route = Route::find($id);
        $route->operator_id = request()->input('operator_id');
        $route->start_station = request()->input('start_station');
        $route->end_station = request()->input('end_station');
        $route->price = str_replace(',', '', request()->input('price'));
        $route->return_price = str_replace(',', '', request()->input('return_price'));
        $route->status = request()->input('status');
        $route->save();

        //clear stopover routes dba_firstke
        $overs = $route->stopovers()->delete();
        //stop over routes
        $stopovers = request()->input('stopover_id') ?? 0;
        if ($stopovers != 0) {
            $order = request()->input('stopover_order');
            foreach ($stopovers as $index => $stopover_id) {
                $stopover = new StopoverRoute();
                $stopover->route_id = $route->id;
                $stopover->stopover_id = $stopover_id;
                $stopover->order = $order[$index];
                $stopover->save();
            }
        }

        $alerts = [
        'bustravel-flash'         => true,
        'bustravel-flash-type'    => 'success',
        'bustravel-flash-title'   => 'Route Updating',
        'bustravel-flash-message' => 'Route has successfully been updated',
    ];

        return redirect()->route('bustravel.routes.edit', $id)->with($alerts);
    }

    //Delete Route
    public function delete($id)
    {
        $route = Route::find($id);
        $name = $route->start->name.' - '.$route->end->name;
        $route->delete();
        $alerts = [
            'bustravel-flash'         => true,
            'bustravel-flash-type'    => 'error',
            'bustravel-flash-title'   => 'Route Deleting ',
            'bustravel-flash-message' => 'Route '.$name.' has successfully been Deleted',
        ];

        return Redirect::route('bustravel.routes')->with($alerts);
    }
}
