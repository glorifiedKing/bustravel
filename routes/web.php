<?php

Route::get('test-backend', 'TestController@index')->name('bustravel.testdefault');
Route::get('test-frontend', 'TestController@front')->name('bustravel.testfront');
Route::get('stations', 'StationsController@index')->name('bustravel.stations');
Route::get('stations/create', 'StationsController@show')->name('bustravel.stations.create');
Route::post('stations/create', 'StationsController@store')->name('bustravel.stations.store');
Route::get('stations/view/{id}', 'StationsController@show')->name('bustravel.stations.edit');
Route::post('stations/view/{id}', 'StationsController@store')->name('bustravel.stations.update');
Route::get('stations/delete/{id}', 'StationsController@destroy')->name('bustravel.stations.delete');
Route::get('general_settings', 'SettingsController@general')->name('bustravel.general_settings');
Route::get('company_settings', 'SettingsController@company')->name('bustravel.company_settings');
Route::get('company_settings/fields', 'SettingsController@fields')->name('bustravel.company_settings.fields');
Route::post('company_settings/fields', 'SettingsController@storefields')->name('bustravel.company_settings.fields.store');
Route::any('company_settings/fields/{id}/update', 'SettingsController@updatefields')->name('bustravel.company_settings.fields.update');
Route::any('company_settings/fields/{id}/delete', 'SettingsController@deletefields')->name('bustravel.company_settings.fields.delete');

//operators routes
Route::get('operators', 'OperatorsController@index')->name('bustravel.operators');
Route::get('operators/create', 'OperatorsController@create')->name('bustravel.operators.create');
Route::post('operators', 'OperatorsController@store')->name('bustravel.operators.store');
Route::get('operators/{id}/edit', 'OperatorsController@edit')->name('bustravel.operators.edit');
Route::any('operators/{id}/update', 'OperatorsController@update')->name('bustravel.operators.update');
Route::any('operators/{id}/delete', 'OperatorsController@delete')->name('bustravel.operators.delete');
//Buses
Route::get('buses', 'BusesController@index')->name('bustravel.buses');
Route::get('buses/create', 'BusesController@create')->name('bustravel.buses.create');
Route::post('buses', 'BusesController@store')->name('bustravel.buses.store');
Route::get('buses/{id}/edit', 'BusesController@edit')->name('bustravel.buses.edit');
Route::any('buses/{id}/update', 'BusesController@update')->name('bustravel.buses.update');
Route::any('buses/{id}/delete', 'BusesController@delete')->name('bustravel.buses.delete');
//Routes
Route::get('routes', 'RouteController@index')->name('bustravel.routes');
Route::get('routes/create', 'RouteController@create')->name('bustravel.routes.create');
Route::post('routes', 'RouteController@store')->name('bustravel.routes.store');
Route::get('routes/{id}/edit', 'RouteController@edit')->name('bustravel.routes.edit');
Route::any('routes/{id}/update', 'RouteController@update')->name('bustravel.routes.update');
Route::any('routes/{id}/delete', 'RouteController@delete')->name('bustravel.routes.delete');
//Drivers
Route::get('drivers', 'DriversController@index')->name('bustravel.drivers');
Route::get('drivers/create', 'DriversController@create')->name('bustravel.drivers.create');
Route::post('drivers', 'DriversController@store')->name('bustravel.drivers.store');
Route::get('drivers/{id}/edit', 'DriversController@edit')->name('bustravel.drivers.edit');
Route::any('drivers/{id}/update', 'DriversController@update')->name('bustravel.drivers.update');
Route::any('drivers/{id}/delete', 'DriversController@delete')->name('bustravel.drivers.delete');
//Route Departure Times
Route::get('routes/departures', 'RoutesDepartureTimesController@index')->name('bustravel.routes.departures');
Route::get('routes/departures/create/{id}/', 'RoutesDepartureTimesController@create')->name('bustravel.routes.departures.create');
Route::post('routes/departures/create', 'RoutesDepartureTimesController@create')->name('bustravel.routes.departures.route_times');
Route::post('routes/departures', 'RoutesDepartureTimesController@store')->name('bustravel.routes.departures.store');
Route::get('routes/departures/{id}/edit', 'RoutesDepartureTimesController@edit')->name('bustravel.routes.departures.edit');
Route::any('routes/departures/{id}/update', 'RoutesDepartureTimesController@update')->name('bustravel.routes.departures.update');
Route::any('routes/departures/{id}/delete', 'RoutesDepartureTimesController@delete')->name('bustravel.routes.departures.delete');


Route::get('bookings', 'BookingsController@index')->name('bustravel.bookings');
Route::get('bookings/create', 'BookingsController@create')->name('bustravel.bookings.create');
Route::post('bookings', 'BookingsController@store')->name('bustravel.bookings.store');
Route::get('bookings/{id}/edit', 'BookingsController@edit')->name('bustravel.bookings.edit');
Route::any('bookings/{id}/update', 'BookingsController@update')->name('bustravel.bookings.update');
Route::any('bookings/{id}/delete', 'BookingsController@delete')->name('bustravel.bookings.delete');

//users

//permissions
Route::get('users/permissions', 'UsersController@permissions')->name('bustravel.users.permissions');
Route::post('users/permissions', 'UsersController@storepermissions')->name('bustravel.users.permissions.store');
Route::any('users/permissions/{id}/update', 'UsersController@updatepermissions')->name('bustravel.users.permissions.update');
Route::any('users/permissions/{id}/delete', 'UsersController@deletepermissions')->name('bustravel.users.permissions.delete');
//roles
Route::get('users/roles', 'UsersController@roles')->name('bustravel.users.roles');
Route::get('users/roles/create', 'UsersController@createroles')->name('bustravel.users.roles.create');
Route::post('users/roles', 'UsersController@storeroles')->name('bustravel.users.roles.store');
Route::get('users/roles/{id}/edit', 'UsersController@editroles')->name('bustravel.users.roles.edit');
Route::any('users/roles/{id}/update', 'UsersController@updateroles')->name('bustravel.users.roles.update');
Route::any('users/roles/{id}/delete', 'UsersController@deleteroles')->name('bustravel.users.roles.delete');
//users
Route::get('users', 'UsersController@users')->name('bustravel.users');
Route::get('users/create', 'UsersController@createusers')->name('bustravel.users.create');
Route::post('users', 'UsersController@storeusers')->name('bustravel.users.store');
Route::get('users/{id}/edit', 'UsersController@editusers')->name('bustravel.users.edit');
Route::any('users/{id}/update', 'UsersController@updateusers')->name('bustravel.users.update');
Route::any('users/{id}/delete', 'UsersController@deleteusers')->name('bustravel.users.delete');
// errors
Route::get('error/access_denied', 'ExceptionsController@accessDenied')->name('bustravel.errors.403');

Route::get('report_sales', 'ReportsController@sales')->name('bustravel.reports.sales');
Route::post('report_sales', 'ReportsController@sales')->name('bustravel.reports.sales.period');
Route::get('report_routes', 'ReportsController@routes')->name('bustravel.reports.profitroute');
Route::post('report_routes', 'ReportsController@routes')->name('bustravel.reports.profitroute.period');
Route::get('report_traffic', 'ReportsController@traffic')->name('bustravel.reports.traffic');
Route::post('report_traffic', 'ReportsController@traffic')->name('bustravel.reports.traffic.period');
Route::get('report_booking', 'ReportsController@booking')->name('bustravel.reports.bookings');
Route::post('report_booking', 'ReportsController@booking')->name('bustravel.reports.bookings.search');
Route::get('report_locations', 'ReportsController@locations')->name('bustravel.reports.locations');
