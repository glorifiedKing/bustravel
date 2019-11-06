<?php 
namespace glorifiedking\BusTravel\Http\Controllers;

use Illuminate\Routing\Controller;

class TestController extends Controller 
{
    public function index()
    {
        return view('bustravel::backend.stations.index');
    }

    public function front()
    {
        return view('bustravel::frontend.test');
    }
}