<?php

namespace glorifiedking\BusTravel\Http\Controllers;

use Carbon\Carbon;
use glorifiedking\BusTravel\Route;
use glorifiedking\BusTravel\RoutesDepartureTime;
use glorifiedking\BusTravel\Station;
use glorifiedking\BusTravel\Faq;
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
        $route_results = Route::where([
            ['start_station', '=', $request->departure_station],
            ['end_station', '=', $request->to_station],
        ])->get();
        if ($route_results->isEmpty()) {

            //check if bus is full

            //filter by time
        }
        $date_of_travel = Carbon::parse($request->date_of_travel)->format('Y-m-d');

        return view('bustravel::frontend.route_search_results', compact('route_results', 'date_of_travel'));
    }

    public function cart(Request $request)
    {
        //get cart items
        $cart = $request->session()->get('cart.items') ?? [];
        $departure_ids = (!empty($cart)) ? array_column($cart, 'id') : [0];
        $route_departures = RoutesDepartureTime::whereIn('id', $departure_ids)->get();

        return view('bustravel::frontend.cart', compact('route_departures'));
    }

    public function add_to_basket(Request $request, $route_departure_time_id, $date_of_travel)
    {
        $route_time = RoutesDepartureTime::findOrFail($route_departure_time_id);
        if ($request->session()->has('cart')) {
            if (!in_array($route_departure_time_id, array_column($request->session()->get('cart.items'), 'id'))) {
                $request->session()->push('cart.items', [
                     'id'             => $route_departure_time_id,
                     'quantity'       => 1,
                     'amount'         => $route_time->route->price,
                     'date_of_travel' => $date_of_travel,
                ]);
            }
        } elseif (!$request->session()->has('cart')) {
            $request->session()->put('cart.items', []);
            $request->session()->push('cart.items', [
                'id'             => $route_departure_time_id,
                'quantity'       => 1,
                'amount'         => $route_time->route->price,
                'date_of_travel' => $date_of_travel,
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
