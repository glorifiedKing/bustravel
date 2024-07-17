<?php

namespace glorifiedking\BusTravel\Http\Controllers;

use glorifiedking\BusTravel\Bus;
use glorifiedking\BusTravel\Card;
use glorifiedking\BusTravel\Operator;
use glorifiedking\BusTravel\Driver;
use glorifiedking\BusTravel\Route;
use glorifiedking\BusTravel\RoutesDepartureTime;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use glorifiedking\BusTravel\ToastNotification;

class CardsController extends Controller
{

    public function __construct()
    {
        $this->middleware('web');
        $this->middleware('auth');
        $this->middleware('can:View BT Buses');
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
}
