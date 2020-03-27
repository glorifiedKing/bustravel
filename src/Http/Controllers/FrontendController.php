<?php

namespace glorifiedking\BusTravel\Http\Controllers;

use Carbon\Carbon;
use glorifiedking\BusTravel\Route;
use glorifiedking\BusTravel\RoutesDepartureTime;
use glorifiedking\BusTravel\RoutesStopoversDepartureTime;
use glorifiedking\BusTravel\Station;
use glorifiedking\BusTravel\Faq;
use glorifiedking\BusTravel\StopoverStation;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FrontendController extends Controller
{
    public function __construct()
    {
        $this->middleware('web')->only('checkout');
        $this->middleware('auth')->only('checkout');
    }

    public function homepage()
    {
        $bus_stations = Station::all();

        return view('bustravel::frontend.index', compact('bus_stations'));
    }

    public function search_routes(Request $request)
    {
        $validated_data = $request->validate([
            'to_station'        => 'required|numeric|different:departure_station',
            'departure_station' => 'required|numeric',
            'date_of_travel'    => 'required|date',
            'time_of_travel'    => 'required',
            'adults'            => 'numeric|min:1',
        ]);

        //search

        // get which day of the week it is for date 
        $travel_day_of_week = Carbon::parse($request->date_of_travel)->format('l');
        $travel_time = Carbon::parse($request->time_of_travel)->format('G:i');
        //dd($travel_day_of_week);
        $departure_time = RoutesDepartureTime::where('days_of_week', 'like', "%$travel_day_of_week%")->whereTime('departure_time','>',$travel_time)->get();   
        //dd($departure_time);
        // first search for main route 
        $route_results = Route::with(['departure_times' => function ($query) use($travel_day_of_week,$travel_time) {
            $query->where('days_of_week', 'like', "%$travel_day_of_week%")->whereTime('departure_time','>',$travel_time);
        }])->where([
            ['start_station', '=', $request->departure_station],
            ['end_station', '=', $request->to_station],            
        ])->get();
        //dd($route_results);  
        if ($route_results->isEmpty()) {

            //check if bus is full

            //filter by time
        }
        $stop_over_routes = StopoverStation::with(['departure_times' => function ($query) use($travel_day_of_week,$travel_time) {
            
            $query->whereHas('main_route_departure_time', function ($query) use($travel_day_of_week) {
                $query->where('days_of_week', 'like', "%$travel_day_of_week%");
            });
            $query->whereTime('arrival_time','>',$travel_time);
        },
        
        ])->where([
            ['start_station', '=', $request->departure_station],
            ['end_station', '=', $request->to_station],            
        ])->get();
        $date_of_travel = Carbon::parse($request->date_of_travel)->format('Y-m-d');

        return view('bustravel::frontend.route_search_results', compact('route_results','stop_over_routes', 'date_of_travel'));
    }

    public function cart(Request $request)
    {
        //get cart items
        $cart = $request->session()->get('cart.items') ?? [];
        $main_route_ids = [0];
        $stop_over_route_ids = [0];
        if(!empty($cart))
        {
            foreach($cart as $c)
            {
                if($c['route_type'] == 'main_route')
                {
                    $main_route_ids[] = (int)$c['id'];
                }
                else if($c['route_type'] == 'stop_over_route')
                {
                    $stop_over_route_ids[] = (int)$c['id'];
                }
            }
            
        }
        
        $main_route_departures = RoutesDepartureTime::whereIn('id', $main_route_ids)->get();
        $stop_over_route_departures = RoutesStopoversDepartureTime::whereIn('id',$stop_over_route_ids)->get();

        return view('bustravel::frontend.cart', compact('main_route_departures','stop_over_route_departures'));
    }

    public function add_to_basket(Request $request, $route_departure_time_id, $date_of_travel,$route_type)
    {
        if($route_type == 'main_route')
        {
            $route_time = RoutesDepartureTime::findOrFail($route_departure_time_id);
        }
        else if($route_type == 'stop_over_route')
        {
            $route_time = RoutesStopoversDepartureTime::findOrFail($route_departure_time_id);
        }
        
        if ($request->session()->has('cart')) {
            if (!in_array($route_departure_time_id, array_column($request->session()->get('cart.items'), 'id'))) {
                $request->session()->push('cart.items', [
                     'id'             => $route_departure_time_id,
                     'quantity'       => 1,
                     'amount'         => $route_time->route->price,
                     'date_of_travel' => $date_of_travel,
                     'route_type'     => $route_type,
                ]);
            }
        } elseif (!$request->session()->has('cart')) {
            $request->session()->put('cart.items', []);
            $request->session()->push('cart.items', [
                'id'             => $route_departure_time_id,
                'quantity'       => 1,
                'amount'         => $route_time->route->price,
                'date_of_travel' => $date_of_travel,
                'route_type'     => $route_type,
           ]);
        }

        return redirect()->route('bustravel.cart');
    }

    public function clear_cart(Request $request)
    {
        if ($request->session()->has('cart')) {
            $request->session()->forget('cart');
        }

        return redirect()->route('bustravel.homepage');
    }

    public function remove_cart_item(Request $request, $key)
    {
        if ($request->session()->has('cart')) {
            $request->session()->pull('cart.items', $key);
        }

        return redirect()->route('bustravel.cart');
    }

    public function checkout(Request $request)
    {
        return view('bustravel::frontend.checkout');
    }

    public function bus_times(Request $request)
    {
        $routes_times =RoutesDepartureTime::paginate(10);
        return view('bustravel::frontend.bus_times',compact('routes_times'));
    }
    public function stations(Request $request)
    {
        $stations =Station::orderBy('name','ASC')->paginate(10);
        return view('bustravel::frontend.stations',compact('stations'));
    }

    public function faqs(Request $request)
    {
        $faqs =Faq::paginate(10);
        return view('bustravel::frontend.faqs',compact('faqs'));
    }
}
