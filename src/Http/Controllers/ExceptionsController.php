<?php
namespace glorifiedking\BusTravel\Http\Controllers;

use Illuminate\Routing\Controller;

class ExceptionsController extends Controller
{
    public function accessDenied()
    {
        return view('bustravel::backend.errors.403');
    }
}