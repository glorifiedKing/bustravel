<?php

namespace glorifiedking\BusTravel\Http\Controllers;

use glorifiedking\BusTravel\Operator;
use glorifiedking\BusTravel\Route;
use glorifiedking\BusTravel\Station;
use glorifiedking\BusTravel\StopoverRoute;
use glorifiedking\BusTravel\Bus;
use glorifiedking\BusTravel\Driver;
use glorifiedking\BusTravel\Booking;
use glorifiedking\BusTravel\StopoverStation;
use glorifiedking\BusTravel\RoutesDepartureTime;
use glorifiedking\BusTravel\RoutesStopoversDepartureTime;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use glorifiedking\BusTravel\ToastNotification;
use glorifiedking\BusTravel\Http\Requests\CreateRouteRequest;

class RouteController extends Controller
{
   public $route_price='price',
    $route_return_price='return_price',
    $route_updating='Route Updating',
    $Status ='status',
    $OperatorId='operator_id';
    public function __construct()
    {
        $this->middleware('web');
        $this->middleware('auth');
        $this->middleware('can:View BT Routes')->only('index');
        $this->middleware('can:Create BT Routes')->except('index');
    }

    //fetching buses route('bustravel.buses')
    public function index()
    {
        if(auth()->user()->hasAnyRole('BT Super Admin'))
          {
               $routes = Route::all();
               $services=RoutesDepartureTime::all()->count();
               $drivers =Driver::where($this->Status,1)->count();
               $buses =Bus::where($this->Status,1)->count();
          }
        else
          {
            $routes =Route::where($this->OperatorId,auth()->user()->operator_id)->get();
            $route_ids =Route::where($this->OperatorId,auth()->user()->operator_id)->pluck('id');
            $services=RoutesDepartureTime::whereIn('route_id',$route_ids)->count();
            $drivers =Driver::where($this->OperatorId,auth()->user()->operator_id)->where($this->Status,1)->count();
            $buses =Bus::where($this->OperatorId,auth()->user()->operator_id)->where($this->Status,1)->count();

          }

        return view('bustravel::backend.routes.index', compact('routes','services','drivers','buses'));
    }
    //creating buses form route('bustravel.buses.create')
    public function create()
    {
        $drivers = Driver::where('status', 1)->where($this->OperatorId,auth()->user()->operator_id)->orderBy('name', 'ASC')->get();
        $buses = Bus::where('status', 1)->where($this->OperatorId,auth()->user()->operator_id)->get();


        return view('bustravel::backend.routes.create', compact('buses', 'drivers'));
    }
     function sort_order($a,$b) {
      return $a['order']>$b['order'];
    }

    // saving a new buses in the database  route('bustravel.buses.store')
    public function store(CreateRouteRequest $request)
    {

      $all_routes = $request->routes;
      $start_station = $all_routes[0]['from'];
      $last_key = array_key_last($all_routes);
      $end_station = $all_routes[$last_key]['to'];

      //get route's of
      $main_route = array_filter($all_routes,function($route) use($start_station,$end_station){
            return ($route['from'] == $start_station && $route['to'] == $end_station);
      });

      $first_key_main_route = array_key_first($main_route);
      $main_route_price = $main_route[$first_key_main_route]['price'];
      $main_route_departure = $main_route[$first_key_main_route]['in'];
      $main_route_arrival = $main_route[$first_key_main_route]['out'];

      // get stop over routes
      unset($all_routes[$first_key_main_route]);
      $stop_over_routes = $all_routes;



        $route = new Route();
        $route->start_station = $start_station;
        $route->end_station = $end_station;
        $route->price = str_replace(',', '', $main_route_price);
        $route->return_price = str_replace(',', '', $main_route_price);
        $route->status = 1;
        $route->save();

        // create main service
        $route_time = new RoutesDepartureTime();
        $route_time->route_id = $route->id;
        $route_time->departure_time = $main_route_departure;
        $route_time->arrival_time = $main_route_arrival;
        $route_time->bus_id = request()->input('bus_id') ?? 0;
        $route_time->driver_id = request()->input('driver_id') ?? 0;
        $route_time->days_of_week = request()->input('days_of_week');
        $route_time->restricted_by_bus_seating_capacity = 1;
        $route_time->status = 1;
        $route_time->save();

        if (!empty($stop_over_routes)) {

            foreach ($stop_over_routes as $index => $stop_over) {
                $stopover = new StopoverStation();
                $stopover->route_id = $route->id;
                $stopover->start_station = $stop_over['from'];
                $stopover->end_station = $stop_over['to'];
                $stopover->price = $stop_over['price'];
                $stopover->order = $stop_over['order'];
                $stopover->save();

                $stop_over_time = new RoutesStopoversDepartureTime();
                $stop_over_time->routes_times_id = $route_time->id;
                $stop_over_time->route_stopover_id = $stopover->id;
                $stop_over_time->arrival_time = $stop_over['out'];
                $stop_over_time->departure_time = $stop_over['in'];
                $stop_over_time->save();



            }
        }
        //Create Route Inverse
        if(request()->input('has_inverse')==1)
        {
          $inverse = new Route();
          $inverse->start_station = $end_station;
          $inverse->end_station = $start_station;
          $inverse->price = str_replace(',', '', $main_route_price);
          $inverse->return_price = str_replace(',', '', $main_route_price);
          $inverse->status = 1;
          $inverse->inverse = $route->id;
          $inverse->save();

          $inverse_route_time = new RoutesDepartureTime();
          $inverse_route_time->route_id = $inverse->id;
          $inverse_route_time->departure_time = $main_route_departure;
          $inverse_route_time->arrival_time = $main_route_arrival;
          $inverse_route_time->bus_id = request()->input('bus_id') ?? 0;
          $inverse_route_time->driver_id = request()->input('driver_id') ?? 0;
          $inverse_route_time->days_of_week = request()->input('days_of_week');
          $inverse_route_time->restricted_by_bus_seating_capacity = 1;
          $inverse_route_time->status = 1;
          $inverse_route_time->save();
          //Inverse stop over routes
          //reverse sort
          usort($stop_over_routes,function($a,$b){
            return $a['order']<$b['order'];
          });
          $inverse_stop_over_routes = $stop_over_routes;

          if (!empty($inverse_stop_over_routes)) {

            foreach ($inverse_stop_over_routes as $index => $stop_over) {
              $inverse_stopover = new StopoverStation();
              $inverse_stopover->route_id = $inverse->id;
              $inverse_stopover->end_station = $stop_over['from'];
              $inverse_stopover->start_station = $stop_over['to'];
              $inverse_stopover->price = $stop_over['price'];
              $inverse_stopover->order = $index;
              $inverse_stopover->save();

              $inverse_stop_over_time = new RoutesStopoversDepartureTime();
              $inverse_stop_over_time->routes_times_id = $inverse_route_time->id;
              $inverse_stop_over_time->route_stopover_id = $inverse_stopover->id;
              $inverse_stop_over_time->arrival_time = $inverse_stop_over_routes[$index+1]['out'] ?? $inverse_stop_over_routes[$index-1]['out'] ??$stop_over['out'];
              $inverse_stop_over_time->departure_time = $inverse_stop_over_routes[$index+1]['in'] ?? $inverse_stop_over_routes[$index-1]['in'] ?? $stop_over['in'];
              $inverse_stop_over_time->save();



            }
        }


        }




        return redirect()->route('bustravel.routes')->withinput()->with(ToastNotification::toast('Route and 1st Bus service have successfully been saved','Routing Saving'));
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
      //  $route->return_price = str_replace(',', '', request()->input($this->route_return_price))??0;
        $route->status = request()->input('status');
        $route->save();

        //clear stopover routes dba_firstke
         $stop_overs_routes =$route->stopovers()->get();
        //stop over routes
        $stopovers = request()->input('routes_from') ?? 0;
        if ($stopovers != 0) {
          $stop_route_id = request()->input('routes_id');
          $endid = request()->input('routes_to');
          $order = request()->input('routes_order');
          $price = request()->input('routes_price');
          foreach($stop_overs_routes as $stop_overs_route){
            if(in_array($stop_overs_route->id,$stop_route_id)==false){
              $stopover_route = StopoverStation::find($stop_overs_route->id);
              $route_times =$stopover_route->departure_times()->pluck('id');
              if(count($route_times)>0)
              {
               $stop_over_bookings =Booking::whereIn('routes_departure_time_id',$route_times)->where('route_type','stop_over_route')->count();
               if($stop_over_bookings==0){
                $stopover_route->delete();
               }
             }else{
               $stopover_route->delete();
             }
            }
          }
          $overs = $route->stopovers()->pluck('id')->all()??[];
          foreach ($stopovers as $index => $stopover_endid) {
            if(in_array($stop_route_id[$index], $overs??[])){
                $stopover = StopoverStation::find($stop_route_id[$index]);
                $stopover->start_station = $stopover_endid;
                $stopover->end_station = $endid[$index];
                $stopover->price = $price[$index];
                $stopover->order = $order[$index];
                $stopover->save();

              }else {
                $stopover = new StopoverStation();
                $stopover->route_id = $route->id;
                $stopover->start_station = $stopover_endid;
                $stopover->end_station = $endid[$index];
                $stopover->price = $price[$index];
                $stopover->order = $order[$index];
                $stopover->save();

              }
          }
        }

        return redirect()->route('bustravel.routes.edit', $id)->with(ToastNotification::toast('Route has successfully been Updated','Route Updating'));
    }

    //Delete Route
    public function delete($id)
    {
        $route = Route::find($id);
        $name = $route->start->name.' - '.$route->end->name;
        $services =$route->departure_times()->count();
        if($services>0){
        return Redirect::route('bustravel.routes')->with(ToastNotification::toast($name. ' cannot be Deleted , it has services','Route Deleting','error'));
        }
        $route->stopovers()->delete();
        $route->delete();
        return Redirect::route('bustravel.routes')->with(ToastNotification::toast($name. ' has successfully been Deleted','Route Deleting','error'));
    }
}
