<?php

namespace glorifiedking\BusTravel\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;



class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('web');
        $this->middleware('auth');
        $this->middleware('can:View BT Stations');

    }

    public function index()
    {
        return view('bustravel::backend.dashboard.main_dashboard');
    }

}
