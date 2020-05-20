<?php

namespace glorifiedking\BusTravel\Http\Controllers;

use glorifiedking\BusTravel\Operator;
use glorifiedking\BusTravel\Route;
use glorifiedking\BusTravel\Station;
use glorifiedking\BusTravel\StopoverRoute;
use glorifiedking\BusTravel\StopoverStation;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use glorifiedking\BusTravel\ToastNotification;

class RouteController extends Controller
{
   public $route_price='price';
   public $route_return_price='return_price';
    public function __construct()
    {
        $this->middleware('web');
        $this->middleware('auth');
    }

    //fetching buses route('bustravel.buses')
    public function index()
    {
        if(auth()->user()->hasAnyRole('BT Administrator'))
          {
            $routes =Route::where('operator_id',auth()->user()->operator_id)->get();
          }
        else
          {
             $routes = Route::all();
          }

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
            return redirect()->route('bustravel.routes.create')->withinput()->with(ToastNotification::toast('Start Station cannot be the same as End Station','Route Error','error'));
        }
        $route = new Route();
        $route->start_station = request()->input('start_station');
        $route->end_station = request()->input('end_station');
        $route->price = str_replace(',', '', request()->input($this->route_price));
        $route->return_price = str_replace(',', '', request()->input($this->route_return_price));
        $route->status = request()->input('status');
        $route->save();
        //stop over routes
        $stopovers = request()->input('stopover_startid') ?? 0;
        if ($stopovers != 0) {
            $endid = request()->input('stopover_endid');
            $order = request()->input('stopover_order');
            $price = request()->input('stopover_price');
            foreach ($stopovers as $index => $stopover_endid) {
                $stopover = new StopoverStation();
                $stopover->route_id = $route->id;
                $stopover->start_station = $stopover_endid;
                $stopover->end_station = $endid[$index];
                $stopover->price = $price[$index];
                $stopover->order = $order[$index];
                $stopover->save();
            }
        }
        //Create Route Inverse
        if(request()->input('has_inverse')==1)
        {
          $inverse = new Route();
          $inverse->start_station = request()->input('end_station');
          $inverse->end_station = request()->input('start_station');
          $inverse->price = str_replace(',', '', request()->input($this->route_return_price));
          $inverse->return_price = str_replace(',', '', request()->input($this->route_price));
          $inverse->status = request()->input('status');
          $inverse->inverse = $route->id;
          $inverse->save();
          //Inverse stop over routes
          $route_stopovers =$route->stopovers()->orderBy('order','DESC')->get();
          if(!is_null($route_stopovers))
          {
            foreach($route_stopovers as $index => $route_stopover)
            {
              $inverse_stopover = new StopoverStation();
              $inverse_stopover->route_id = $inverse->id;
              $inverse_stopover->start_station = $route_stopover->end_station;
              $inverse_stopover->end_station = $route_stopover->start_station;
              $inverse_stopover->price = $route_stopover->price;
              $inverse_stopover->order = $index;
              $inverse_stopover->save();

            }
          }
        }
        return redirect()->route('bustravel.routes.departures.create',$route->id)->withinput()->with(ToastNotification::toast('Route has successfully been saved','Routing Saving'));
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
        if(is_null($route->inverse))
        {
          $inverse_route=Route::where('inverse',$route->id)->first();
          $mainroute =null;
        }else{
          $mainroute=Route::find($route->inverse);
          $inverse_route =null;
        }

        return view('bustravel::backend.routes.edit', compact('bus_operators', 'route', 'stations', 'routes','mainroute','inverse_route'));
    }

    //Update Operator route('bustravel.operators.upadate')
    public function update($id, Request $request)
    {
        //validation
        $validation = request()->validate(Route::$rules);
        if (request()->input('start_station') == request()->input('end_station')) {
            return redirect()->route('bustravel.routes.edit', $id)->withinput()->with(ToastNotification::toast('Start Station cannot be the same as End Station','Route Error','error'));
        }
        //saving to the database
        $route = Route::find($id);
        $route->start_station = request()->input('start_station');
        $route->end_station = request()->input('end_station');
        $route->price = str_replace(',', '', request()->input($this->route_price));
        $route->return_price = str_replace(',', '', request()->input($this->route_return_price));
        $route->status = request()->input('status');
        $route->save();

        //clear stopover routes dba_firstke
        $overs = $route->stopovers()->delete();
        //stop over routes
        $stopovers = request()->input('stopover_startid') ?? 0;
        if ($stopovers != 0) {
          $endid = request()->input('stopover_endid');
          $order = request()->input('stopover_order');
          $price = request()->input('stopover_price');
          foreach ($stopovers as $index => $stopover_endid) {
              $stopover = new StopoverStation();
              $stopover->route_id = $route->id;
              $stopover->start_station = $stopover_endid;
              $stopover->end_station = $endid[$index];
              $stopover->price = $price[$index];
              $stopover->order = $order[$index];
              $stopover->save();
          }
        }

        return redirect()->route('bustravel.routes.edit', $id)->with(ToastNotification::toast('Route has successfully been Updated','Route Updating'));
    }

    //Delete Route
    public function delete($id)
    {
        $route = Route::find($id);
        $name = $route->start->name.' - '.$route->end->name;
        $route->departure_times()->delete();
        $route->stopovers()->delete();
        $route->delete();
        return Redirect::route('bustravel.routes')->with(ToastNotification::toast($name. ' has successfully been Deleted','Route Deleting','error'));
    }
}
