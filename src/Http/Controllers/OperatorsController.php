<?php
namespace glorifiedking\BusTravel\Http\Controllers;

use Illuminate\Routing\Controller;

class OperatorsController extends Controller
{
    public function index()
    {
        return view('bustravel::backend.operators.index');
    }
}
