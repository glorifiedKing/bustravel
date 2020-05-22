<?php

namespace glorifiedking\BusTravel\Http\Controllers;

use glorifiedking\BusTravel\BookingCustomField;
use glorifiedking\BusTravel\Operator;
use glorifiedking\BusTravel\GeneralSetting;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use glorifiedking\BusTravel\ToastNotification;

class SettingsController extends Controller
{

    public function __construct()
    {
        $this->middleware('web');
        $this->middleware('auth');
        $this->middleware('can:Manage BT General Settings');
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
        return redirect()->route('bustravel.company_settings.fields')->with(ToastNotification::toast($fields->field_name.'  has successfully been saved','Field Saving'));
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

        return redirect()->route('bustravel.company_settings.fields')->with(ToastNotification::toast('Field has successfully been Updated','Field Updating'));
    }

    //Delete Field
    public function deletefields($id)
    {
        $field = BookingCustomField::find($id);
        $name = $field->field_name;
        $field->delete();
        return Redirect::route('bustravel.company_settings.fields')->with(ToastNotification::toast($name. ' has successfully been Deleted','Field Deleted','error'));
    }

    public function general_settings()
    {

           $settings = GeneralSetting::all();
        return view('bustravel::backend.settings.general_settings', compact('settings'));
    }

    public function store_general_settings(Request $request)
    {
        //validation
        $validation = request()->validate(GeneralSetting::$rules);
        //saving to the database
        $setting = new GeneralSetting();
        $setting->setting_prefix = strtolower(str_replace(' ', '_', request()->input('setting_prefix')));
        $setting->setting_description = request()->input('setting_description');
        $setting->setting_value = request()->input('setting_value');
        $setting->save();
        return redirect()->route('bustravel.general_settings')->with(ToastNotification::toast('General Settings has successfully been saved','General Settings Saving'));
    }

    public function update_general_settings( Request $request)
    {
        //validation
        //$validation = request()->validate(BookingCustomField::$rules);
        //saving to the database
        $ids = request()->input('id');
        $description = request()->input('setting_description');
        $value = request()->input('setting_value');

        foreach($ids as $index => $id )
        {
          $setting =  GeneralSetting::find($id);
          $setting->setting_description = $description[$index];
          $setting->setting_value = $value[$index];
          $setting->save();
        }

        return redirect()->route('bustravel.general_settings')->with(ToastNotification::toast('Successfully  Updated','Setting Updating'));
    }

    //Delete Field

}
