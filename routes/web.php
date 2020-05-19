<?php

Route::get('test-backend', 'TestController@index')->name('bustravel.testdefault');
Route::get('test-frontend', 'TestController@front')->name('bustravel.testfront');
Route::get('stations', 'StationsController@index')->name('bustravel.stations');
Route::get('stations/create', 'StationsController@show')->name('bustravel.stations.create');
Route::post('stations/create', 'StationsController@store')->name('bustravel.stations.store');
Route::get('stations/view/{id}', 'StationsController@show')->name('bustravel.stations.edit');
Route::post('stations/view/{id}', 'StationsController@store')->name('bustravel.stations.update');
Route::get('stations/delete/{id}', 'StationsController@destroy')->name('bustravel.stations.delete');
Route::get('general_settings', 'SettingsController@general_settings')->name('bustravel.general_settings');
Route::post('general_settings', 'SettingsController@store_general_settings')->name('bustravel.general_settings.store');
Route::post('general_settings/update', 'SettingsController@update_general_settings')->name('bustravel.general_settings.update');
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
Route::any('bookings/boarded/{id}', 'BookingsController@boarded')->name('bustravel.bookings.boarded');
Route::get('bookings/{id}/edit', 'BookingsController@edit')->name('bustravel.bookings.edit');
Route::any('bookings/{id}/update', 'BookingsController@update')->name('bustravel.bookings.update');
Route::any('bookings/{id}/delete', 'BookingsController@delete')->name('bustravel.bookings.delete');

Route::get('route/tracking/{id}', 'BookingsController@route_tracking')->name('bustravel.bookings.route.tracking');
Route::get('route/tracking/start/{id}', 'BookingsController@route_tracking_start')->name('bustravel.bookings.route.tracking.start');
Route::get('route/tracking/end/{id}', 'BookingsController@route_tracking_end')->name('bustravel.bookings.route.tracking.end');
Route::get('driver/manifest', 'BookingsController@manifest')->name('bustravel.bookings.manifest');
Route::get('route/manifest/{id}', 'BookingsController@route_manifest')->name('bustravel.bookings.route.manifest');
Route::post('route/manifest/{id}', 'BookingsController@route_manifest')->name('bustravel.bookings.route.manifest.search');
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
Route::get('users/changepassword', 'UsersController@changepassword')->name('bustravel.users.changepassword');
Route::post('users/changepassword/save', 'UsersController@changepassword_save')->name('bustravel.users.changepassword.save');
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
Route::get('cashier/report', 'ReportsController@cashier_report')->name('bustravel.bookings.cashier.report');
Route::post('cashier/report', 'ReportsController@cashier_report')->name('bustravel.bookings.cashier.report.search');

Route::get('faqs', 'FaqsController@faqs')->name('bustravel.faqs');
Route::post('faqs', 'FaqsController@storefaqs')->name('bustravel.faqs.store');
Route::any('faqs/{id}/update', 'FaqsController@updatefaqs')->name('bustravel.faqs.update');
Route::any('faqs/{id}/delete', 'FaqsController@deletefaqs')->name('bustravel.faqs.delete');

// email templates
Route::get('email_template','TicketTemplateController@view_email_templates')->name('bustravel.email.templates');
Route::get('email_template/new','TicketTemplateController@create_email_template')->name('bustravel.email.templates.create');
Route::post('email_template/new','TicketTemplateController@save_email_template')->name('bustravel.email.templates.save');
Route::get('email_template/{id}/edit','TicketTemplateController@edit_email_template')->name('bustravel.email.templates.edit');
Route::post('email_template/{id}/edit','TicketTemplateController@update_email_template')->name('bustravel.email.templates.update');
Route::get('email_template/{id}/delete','TicketTemplateController@delete_email_template')->name('bustravel.email.templates.delete');


// sms templates 
Route::get('sms_template','TicketTemplateController@view_sms_templates')->name('bustravel.sms.templates');
Route::get('sms_template/new','TicketTemplateController@create_sms_template')->name('bustravel.sms.templates.create');
Route::post('sms_template/new','TicketTemplateController@save_sms_template')->name('bustravel.sms.templates.save');
Route::get('sms_template/{id}/edit','TicketTemplateController@edit_sms_template')->name('bustravel.sms.templates.edit');
Route::post('sms_template/{id}/edit','TicketTemplateController@update_sms_template')->name('bustravel.sms.templates.update');
Route::get('sms_template/{id}/delete','TicketTemplateController@delete_sms_template')->name('bustravel.sms.templates.delete');

// printers
Route::get('printers','PrintersController@view_printers')->name('bustravel.printers.list');
Route::get('printers/new','PrintersController@create_printer')->name('bustravel.printers.create');
Route::post('printers/new','PrintersController@save_printer')->name('bustravel.printers.save');
Route::get('printers/{id}/edit','PrintersController@edit_printer')->name('bustravel.printers.edit');
Route::post('printers/{id}/edit','PrintersController@update_printer')->name('bustravel.printers.update');
Route::get('printers/{id}/delete','PrintersController@delete_printer')->name('bustravel.printers.delete');


Route::post('api/getBusServices/{operatorId}','BookingsController@get_route_times')->name('bustravel.api.get.route.times');

// payment reports 
Route::get('report_payments/{start_date?}','PaymentReportsController@list')->name('bustravel.reports.payments');
