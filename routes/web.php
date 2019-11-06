<?php

Route::get('test-backend','TestController@index')->name('bustravel.testdefault');
Route::get('test-frontend','TestController@front')->name('bustravel.testfront');
Route::get('stations','StationsController@index')->name('bustravel.stations');
Route::get('general_settings','SettingsController@general')->name('bustravel.general_settings');
Route::get('company_settings','SettingsController@company')->name('bustravel.company_settings');
Route::get('operators','OperatorsController@index')->name('bustravel.operators');
Route::get('buses','BusesController@index')->name('bustravel.buses');
Route::get('routes','BusRoutesController@index')->name('bustravel.routes');
Route::get('drivers','DriversController@index')->name('bustravel.drivers');
Route::get('bookings','BookingsController@index')->name('bustravel.bookings');
Route::get('report_sales','ReportsController@sales')->name('bustravel.reports.sales');
Route::get('report_routes','ReportsController@routes')->name('bustravel.reports.profitroute');
Route::get('report_traffic','ReportsController@traffic')->name('bustravel.reports.traffic');
Route::get('report_booking','ReportsController@booking')->name('bustravel.reports.bookings');
Route::get('report_locations','ReportsController@locations')->name('bustravel.reports.locations');