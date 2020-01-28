<?php

namespace glorifiedking\BusTravel\Http\Controllers;

use glorifiedking\BusTravel\BookingCustomField;
use glorifiedking\BusTravel\Operator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('web');
        $this->middleware('auth');
    }

    //fetching operators route('bustravel.operators')
    public function fields()
    {
      if(auth()->user()->hasAnyRole('BT Administrator'))
        {
          $fields = BookingCustomField::where('operator_id',auth()->user()->operator_id)->get();
        }
      else
        {
           $fields = BookingCustomField::all();
        }
        return view('bustravel::backend.settings.custom_fields', compact('fields'));
    }

    public function storefields(Request $request)
    {
        //validation
        $validation = request()->validate(BookingCustomField::$rules);
        //saving to the database
        $fields = new BookingCustomField();
        $fields->field_prefix = strtolower(str_replace(' ', '_', request()->input('field_name')));
        $fields->field_name = request()->input('field_name');
        $fields->field_order = request()->input('field_order') ?? 0;
        $fields->is_required = request()->input('is_required');
        $fields->status = request()->input('status');
        $fields->save();
        $alerts = [
        'bustravel-flash'         => true,
        'bustravel-flash-type'    => 'success',
        'bustravel-flash-title'   => 'Field Saving',
        'bustravel-flash-message' => 'Field has successfully been saved',
    ];

        return redirect()->route('bustravel.company_settings.fields')->with($alerts);
    }

    public function updatefields($id, Request $request)
    {
        //validation
        $validation = request()->validate(BookingCustomField::$rules);
        //saving to the database
        $field_id = request()->input('id');

        $fields = BookingCustomField::find($field_id);
        $fields->field_prefix = strtolower(str_replace(' ', '_', request()->input('field_name')));
        $fields->field_name = request()->input('field_name');
        $fields->field_order = request()->input('field_order') ?? 0;
        $fields->is_required = request()->input('is_required');
        $fields->status = request()->input('status');
        $fields->save();
        $alerts = [
        'bustravel-flash'         => true,
        'bustravel-flash-type'    => 'success',
        'bustravel-flash-title'   => 'Field Updating',
        'bustravel-flash-message' => 'Field has successfully been Updated',
    ];

        return redirect()->route('bustravel.company_settings.fields')->with($alerts);
    }

    //Delete Field
    public function deletefields($id)
    {
        $field = BookingCustomField::find($id);
        $name = $field->field_name;
        $field->delete();
        $alerts = [
            'bustravel-flash'         => true,
            'bustravel-flash-type'    => 'error',
            'bustravel-flash-title'   => 'Field Deleted',
            'bustravel-flash-message' => "Field '.$name.' has successfully been deleted",
        ];

        return Redirect::route('bustravel.company_settings.fields')->with($alerts);
    }
}
