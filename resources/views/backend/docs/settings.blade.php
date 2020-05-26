@extends('bustravel::backend.docs.layout')

@section('title', 'PalmKash User Manual')



@section('content') 
    <h4 id="account_login" class="mb-4">Company settings</h4>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 pt-6">
            <h5>Booking custom fields</h5>
            <p>Booking custom fields are created by operators to capture information about a customer at the point of ticket purchase through a cashier.
                These fields would appear on the ticket purchase screen in addition to the default fields already existent.</p>
                <ol>
                    <li>In the lefthand sidebar menu click <strong>Settings</strong> then <strong>Company Settings</strong>, and from the dropdown options select <strong>Booking Custom Fields</strong>.
                    This will load the list of available booking custom fields with options to Edit or Delete them using the links in the <strong>Action</strong> column.</li>
                    <li>To add a booking custom field, click the plus (+) button and select New Field.</li>
                    <li>Fill in the field's name, select whether it should be a required field, set it status as active, then save it.</li>
                    <li>Your custom field will now appear on the ticket booking form for cashiers.</li>
                </ol>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mt-4">
            <h6>Booking custom field creation</h6>
            <img class="demo_image" src="{{asset('vendor/glorifiedking/docs/images/booking_custom_field.png') }}"/>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <h4 id="change_my_password" class="mb-4 mt-5 pt-5">Ticket printers</h4>
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 pt-6">
                <p>
                    <ol>
                    <li>In the lefthand sidebar menu click <strong>Settings</strong> then <strong>Company Settings</strong>, and from the dropdown options select <strong>Printers</strong>.
                        This will load the list of available ticket printers options to Edit or Delete them using the links in the <strong>Action</strong> column.</li>
                    <li>To add a printer, click the plus (+) button and select New Printer.</li>
                    <li>Fill in the Printer name, Url or Ip address, and port number, then save it.</li>
                    <li>Your printer will now appear in the list of available printing options on the cashier's booking form.</li>
                    </ol>
                </p>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mt-4">
            <h6>Adding a ticket printer</h6>
            <img class="demo_image" src="{{asset('vendor/glorifiedking/docs/images/add_printer.png') }}"/>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <h4 id="change_my_password" class="mb-4 mt-5 pt-5">Email templates</h4>
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 pt-6">
                <p>Email templates are used by the system to format the emais received by a customer when they book a ticket through the website frontend.
                Basic HTML code can be used in the templates.</p>
                <ol>
                    <li>In the lefthand sidebar menu click <strong>Settings</strong> then <strong>Company Settings</strong>, and from the dropdown options select <strong>Email Templates</strong>.
                    This will load the list of available email templates with options to Edit or Delete them using the links in the <strong>Action</strong> column.</li>
                    <li>To add an email template, click the plus (+) button and select New Email Template.</li>
                    <li>Fill in details for the Purpose and language. Formulate the email template using the variables given above, for example;
                    {FIRST_NAME} - will print the Firt name of the customer, {TICKET_NO} will print the Ticket number into the email that will be sent to the customer.</li>
                    <li>Finally you can submit/save.</li>
                    <li>Your email template is now ready for use by the system.</li>
                </ol>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mt-4">
            <h6>Add an Email Template</h6>
            <img class="demo_image" src="{{asset('vendor/glorifiedking/docs/images/add_email_template.png') }}"/>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <h4 id="change_my_password" class="mb-4 mt-5 pt-5">SMS Templates</h4>
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 pt-6">
            <p>SMS templates are used by the system to format the SMS received by a customer when they book a ticket through the website frontend
                or through <strong>USSD</strong>. This template <strong>DOES NOT SUPPORT</strong> HTML code.</p>
                <ol>
                    <li>In the lefthand sidebar menu click <strong>Settings</strong> then <strong>Company Settings</strong>, and from the dropdown options select <strong>Sms Templates</strong>.
                    This will load the list of available SMS templates with options to Edit or Delete them using the links in the <strong>Action</strong> column.</li>
                    <li>To add an SMS template, click the plus (+) button and select New Sms Template.</li>
                    <li>Fill in details for the Purpose and language. Formulate the SMS template using the variables given above, for example;
                    {FIRST_NAME} - will print the Firt name of the customer, {TICKET_NO} will print the Ticket number into the SMS that will be sent to the customer.
                    <br><strong>Note:</strong>One SMS message would be the equivalent of 160 characters including the booking variables generated from the system.</li>
                    <li>Finally you can submit/save.</li>
                    <li>Your SMS template is now ready for use by the system.</li>
                </ol>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mt-4">
            <h6>Add an SMS Template</h6>
            <img class="demo_image" src="{{asset('vendor/glorifiedking/docs/images/add_sms_template.png') }}"/>
            </div>
        </div>
    </div>
@stop  
