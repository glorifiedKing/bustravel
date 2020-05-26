<?php

namespace glorifiedking\BusTravel\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;



class DocumentationController extends Controller
{
    public function __construct()
    {
        $this->middleware('web');
        $this->middleware('auth');
        $this->middleware('can:View BT Stations');
        
    }

    public function index()
    {
        return view('bustravel::backend.docs.index');
    }

    public function buses()
    {
        return view('bustravel::backend.docs.buses'); 
    }

    public function drivers()
    {
        return view('bustravel::backend.docs.drivers'); 
    }

    public function routes()
    {
        return view('bustravel::backend.docs.routes'); 
    }

    public function bookings()
    {
        return view('bustravel::backend.docs.bookings'); 
    }

    public function reports()
    {
        return view('bustravel::backend.docs.reports'); 
    }

    public function settings()
    {
        return view('bustravel::backend.docs.settings'); 
    }

}