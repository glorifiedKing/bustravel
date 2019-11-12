<?php
namespace glorifiedking\BusTravel\Http\Controllers;

use Illuminate\Routing\Controller;

class FrontendController extends Controller
{
    public function homepage()
    {
        return view('bustravel::frontend.index');
    }
}