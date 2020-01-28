<?php

namespace glorifiedking\BusTravel\Http\Controllers;

use glorifiedking\BusTravel\Bus;
use glorifiedking\BusTravel\Driver;
use glorifiedking\BusTravel\Operator;
use glorifiedking\BusTravel\Route;
use glorifiedking\BusTravel\RoutesDepartureTime;
use glorifiedking\BusTravel\RoutesStopoversDepartureTime;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;

class RoutesDepartureTimesController extends Controller
{
    public function __construct()
    {
        $this->middleware('web');
        $this->middleware('auth');
    }

    //fetching buses route('bustravel.buses')
    public function index()
    {
        $routes = RoutesDepartureTime::all();

        return view('bustravel::backend.routes_departures.index', compact('routes'));
    }

    //creating buses form route('bustravel.buses.create')
    public function create($id)
    {

        $route =Route::find($id);
        $routes = Route::where('status', 1)->get();
        $drivers = Driver::where('status', 1)->where('operator_id',auth()->user()->operator_id)->orderBy('name', 'ASC')->get();
        $buses = Bus::where('status', 1)->where('operator_id',auth()->user()->operator_id)->get();

        return view('bustravel::backend.routes_departures.create', compact('buses', 'routes', 'drivers','route'));
    }

    // saving a new route departure times in the database  route('bustravel.routes.departures.store')
    public function store(Request $request)
    {
        //validation
        $validation = request()->validate(RoutesDepartureTime::$rules);
        //saving to the database
        $route = new RoutesDepartureTime();
        $route->route_id = request()->input('route_id');
        $route->departure_time = request()->input('departure_time');
        $route->arrival_time = request()->input('arrival_time');
        $route->bus_id = request()->input('bus_id') ?? 0;
        $route->driver_id = request()->input('driver_id') ?? 0;
        $route->days_of_week = request()->input('days_of_week');
        $route->restricted_by_bus_seating_capacity = request()->input('restricted_by_bus_seating_capacity');
        $route->status = request()->input('status');
        $route->save();
        $stopovers = request()->input('stopover_routeid') ?? 0;
        if ($stopovers != 0) {
            $arrival = request()->input('stopover_arrival_time');
            $departure = request()->input('stopover_departure_time');
            foreach ($stopovers as $index => $stopover_routeid) {
                $stopover = new RoutesStopoversDepartureTime();
                $stopover->routes_times_id = $route->id;
                $stopover->route_stopover_id = $stopover_routeid;
                $stopover->arrival_time = $arrival[$index];
                $stopover->departure_time = $departure[$index];
                $stopover->save();
            }
        }
        $alerts = [
        'bustravel-flash'         => true,
        'bustravel-flash-type'    => 'success',
        'bustravel-flash-title'   => 'Route Saving',
        'bustravel-flash-message' => 'Route has successfully been saved',
    ];

        return redirect()->route('bustravel.routes.edit',$route->route_id)->with($alerts);
    }

    //Bus Edit form route('bustravel.buses.edit')
    public function edit($id)
    {
        $routes = Route::where('status', 1)->get();
        $drivers = Driver::where('status', 1)->orderBy('name', 'ASC')->get();
        $buses = Bus::where('status', 1)->get();
        $route_departure_time = RoutesDepartureTime::find($id);
        if (is_null($route_departure_time)) {
            return Redirect::route('bustravel.routes.departures');
        }

        return view('bustravel::backend.routes_departures.edit', compact('buses', 'routes', 'drivers', 'route_departure_time'));
    }

    //Update Operator route('bustravel.operators.upadate')
    public function update($id, Request $request)
    {
        //validation
        $validation = request()->validate(RoutesDepartureTime::$rules);
        //saving to the database
        $route = RoutesDepartureTime::find($id);
        $route->route_id = request()->input('route_id');
        $route->departure_time = request()->input('departure_time');
        $route->arrival_time = request()->input('arrival_time');
        $route->bus_id = request()->input('bus_id') ?? 0;
        $route->driver_id = request()->input('driver_id') ?? 0;
        $route->restricted_by_bus_seating_capacity = request()->input('restricted_by_bus_seating_capacity');
        $route->days_of_week = request()->input('days_of_week');
        $route->status = request()->input('status');
        $route->save();
        $overs = $route->stopovers_times()->delete();
        $stopovers = request()->input('stopover_routeid') ?? 0;
        if ($stopovers != 0) {
            $arrival = request()->input('stopover_arrival_time');
            $departure = request()->input('stopover_departure_time');
            foreach ($stopovers as $index => $stopover_routeid) {
                $stopover = new RoutesStopoversDepartureTime();
                $stopover->routes_times_id = $route->id;
                $stopover->route_stopover_id = $stopover_routeid;
                $stopover->arrival_time = $arrival[$index];
                $stopover->departure_time = $departure[$index];
                $stopover->save();
            }
        }
        $alerts = [
        'bustravel-flash'         => true,
        'bustravel-flash-type'    => 'success',
        'bustravel-flash-title'   => 'Route Updating',
        'bustravel-flash-message' => 'Route has successfully been updated',
    ];

        return redirect()->route('bustravel.routes.departures.edit', $id)->with($alerts);
    }

    //Delete Route Departure Times
    public function delete($id)
    {
        $routes_departure_time = RoutesDepartureTime::find($id);
        $name = $routes_departure_time->route->start->name.' - '.$routes_departure_time->route->end->name.' at '.$routes_departure_time->departure_time;
        $routes_departure_time->delete();
        $alerts = [
            'bustravel-flash'         => true,
            'bustravel-flash-type'    => 'error',
            'bustravel-flash-title'   => 'Route Departure Time Deleting ',
            'bustravel-flash-message' => 'Route Departure Time '.$name.' has successfully been Deleted',
        ];

        return Redirect::route('bustravel.routes.departures')->with($alerts);
    }
}
