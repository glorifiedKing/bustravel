<?php

namespace glorifiedking\BusTravel\Http\Controllers;

use glorifiedking\BusTravel\Bus;
use glorifiedking\BusTravel\Card;
use glorifiedking\BusTravel\PaymentTransaction;
use glorifiedking\BusTravel\Booking;
use glorifiedking\BusTravel\Route;
use glorifiedking\BusTravel\RoutesDepartureTime;
use glorifiedking\BusTravel\RoutesStopoversDepartureTime;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use glorifiedking\BusTravel\ToastNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CardsController extends Controller
{

    const DAYS_OF_THE_WEEK_STRING = "days_of_week";
    const START_STATION_STRING = "start_station";
    const END_STATION_STRING = "end_station";
    const DEPARTURE_TIME_STRING = "departure_time";
    public function __construct()
    {
        $this->middleware('web')->except('book', 'search_routes', 'stations');
        $this->middleware('auth')->except('book', 'search_routes', 'stations');
        $this->middleware('can:View BT Buses')->except('book', 'search_routes', 'stations');
    }

    //fetching buses route('bustravel.buses')
    public function index()
    {
        if (!auth()->user()->can('View BT Buses')) {
            return redirect()->route('bustravel.errors.403');
        }
        $cards = Card::all();
        return view('bustravel::backend.cards.index', compact('cards'));
    }

    //creating buses form route('bustravel.buses.create')
    public function create()
    {
        return view('bustravel::backend.cards.create');
    }

    // saving a new buses in the database  route('bustravel.buses.store')
    public function store(Request $request)
    {
        //validation
        $validation = request()->validate(Card::$rules);
        //saving to the database
        $card = new Card();
        $card->identifier = request()->input('identifier');
        $card->balance = request()->input('balance');
        $card->name = request()->input('name');
        $card->phone = request()->input('phone');
        $card->national_id = $request->national_id;
        $card->save();
        return redirect()->route('bustravel.cards')->with(ToastNotification::toast('Card has successfully been saved', 'Card Saving'));
    }

    //Bus Edit form route('bustravel.buses.edit')
    public function edit($id)
    {

        $card = Card::find($id);
        if (is_null($card)) {
            return Redirect::route('bustravel.cards');
        }

        return view('bustravel::backend.cards.edit', compact('card'));
    }

    //Update Operator route('bustravel.operators.upadate')
    public function update($id, Request $request)
    {
        //validation
        $validation = request()->validate([
            'identifier'     => 'required|unique:cards,identifier,' . $id,
            'balance' => 'required|integer',
        ]);
        //saving to the database
        $card = Card::find($id);
        $card->identifier = request()->input('identifier');
        $card->balance = request()->input('balance');
        $card->name = request()->input('name');
        $card->phone = request()->input('phone');
        $card->national_id = $request->national_id;
        $card->save();

        return redirect()->route('bustravel.cards.edit', $id)->with(ToastNotification::toast('Card has successfully been updated', 'Card Updating'));
    }

    //Delete Bus
    public function delete($id)
    {
        $card = Card::find($id);
        $name = $card->identifier;
        $card->delete();
        return Redirect::route('bustravel.cards')->with(ToastNotification::toast($name . ' has successfully been Deleted', 'card Deleting', 'error'));
    }

    public function search_routes(Request $request)
    {
        $last_date = Carbon::now()->addWeeks(2)->toDateString();
        $now = Carbon::now()->addMinutes(5);
        $now_string = $now->toDateTimeString();
        $validated_data = $request->validate([
            'to_station'        => 'required|numeric|different:departure_station',
            'departure_station' => 'required|numeric',
            'date_of_travel'    => "required|date|after:yesterday|before:$last_date",
            'time_of_travel'    => 'required',
            'adults'            => 'numeric|min:1',
        ]);

        $selected_date_time = Carbon::parse($request->date_of_travel . " " . $request->time_of_travel);
        if ($selected_date_time < $now) {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                'date_of_travel' => ["date of travel must be after $now_string"],
                'time_of_travel' => ["you need to book at least 5min before departure"],

            ]);
            throw $error;
        }


        // get which day of the week it is for date
        $travel_day_of_week = Carbon::parse($request->date_of_travel)->format('l');
        $travel_time = Carbon::parse($request->time_of_travel)->format('G:i');
        $no_of_tickets = $request->adults;

        $q_start_station = $request->departure_station;
        $q_end_station = $request->to_station;
        //dd($travel_day_of_week);
        $departure_times = RoutesDepartureTime::whereHas('route', function ($q) use ($q_start_station, $q_end_station) {
            $q->where([
                [self::START_STATION_STRING, '=', $q_start_station],
                [self::END_STATION_STRING, '=', $q_end_station],
            ]);
        })->where(self::DAYS_OF_THE_WEEK_STRING, 'like', "%$travel_day_of_week%")->whereTime(self::DEPARTURE_TIME_STRING, '>', $travel_time)->get()->sortBy(self::DEPARTURE_TIME_STRING);


        $date_of_travel = Carbon::parse($request->date_of_travel)->format('Y-m-d');

        $departure_times_stop_over = RoutesStopoversDepartureTime::whereHas('route', function ($q) use ($q_start_station, $q_end_station) {
            $q->where([
                [self::START_STATION_STRING, '=', $q_start_station],
                [self::END_STATION_STRING, '=', $q_end_station],
            ]);
        })->whereHas('main_route_departure_time', function ($query) use ($travel_day_of_week) {
            $query->where(self::DAYS_OF_THE_WEEK_STRING, 'like', "%$travel_day_of_week%");
        })->whereTime(self::DEPARTURE_TIME_STRING, '>', $travel_time)->get()->sortBy(self::DEPARTURE_TIME_STRING);


        return response()->json([
            'status' => 'sucess',
            'result' => [
                'departure_times' => $departure_times,
                'departure_times_stop_over' => $departure_times_stop_over
            ]
        ]);
    }

    public function book(Request $request)
    {

        if (!$request->filled('card_id')) {
            return response('Card ID required', 400);
        }
        if (!$request->filled('route_type')) {
            return response('Route Type required', 400);
        }
        if (!$request->filled('route_id')) {
            return response('Route ID required', 400);
        }
        $card = Card::where('identifier', $request->card_id)->first();

        if (!$card) {
            return response('invalid card', 400);
        }
        $departure_id = $request->route_id;
        $departure_time = ($request->route_type == 'main_route') ? RoutesDepartureTime::findOrFail($departure_id) : RoutesStopOversDepartureTime::findOrFail($departure_id);
        if ($card->balance < $departure_time->route->price) {
            return response('insufficient balance', 400);
        }

        try {
            DB::beginTransaction();
            $transaction = new PaymentTransaction;
            $transaction->payee_reference = $card->identifier;
            $transaction->amount = $departure_time->route->price;
            $transaction->main_routes = ($request->route_type == 'main_route') ? [$departure_id] : NULL;
            $transaction->stop_over_routes = ($request->route_type == 'stop_over_route') ? [$departure_id] : NULL;
            $transaction->user_id = 0;
            $transaction->first_name = $card->name;
            $transaction->last_name = '';
            $transaction->phone_number = $card->phone;
            $transaction->email = $card->email;
            $transaction->address_1 = '';
            $transaction->address_2 = '';
            $transaction->country =  'RW';
            $transaction->send_sms = 0;
            $transaction->send_email = 0;
            $transaction->date_of_travel = date('Y-m-d');
            $transaction->transport_operator_id = $departure_time->route->operator->id;
            $transaction->no_of_tickets = 1;
            $transaction->language = 'english';
            $transaction->payment_gateway = 'tap_and_go';
            $transaction->payment_source = 'tap_and_go';
            $transaction->status = 'completed';
            $transaction->save();

            $booking = new Booking;
            $ticket_number = $this->generateRandomTicket($departure_time->route->operator->code, rand(10, 100));
            $booking->routes_departure_time_id = $departure_id;
            $booking->amount = $departure_time->route->price;
            $booking->date_paid = date('Y-m-d');
            $booking->date_of_travel = $transaction->date_of_travel;
            $booking->time_of_travel = $departure_time->departure_time;
            $booking->ticket_number = $ticket_number;
            $booking->user_id = 0;
            $booking->route_type = $request->route_type;
            $booking->payment_source = 'tap_and_go';
            $booking->payment_transaction_id = $transaction->id;
            $booking->save();
            $card->balance = $card->balance - $departure_time->route->price;
            $card->save();
            DB::commit();
            Log::info("successfull saved tap and go route");
            return response()->json([
                'status' => 'sucess',
                'result' => 'successfull'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info("error saving tap and go");
            return response($e->getMessage(), 500);
        }
    }

    private function generateRandomTicket(string $prefix, int|string $key): string
    {
        $pKey = strval($key);
        return strtoupper(uniqid($prefix) . substr($pKey, strlen($pKey) - 1));
    }
}
