<!doctype html>
<html lang="en">
  <head>
      
  	<title>@yield('title','PalmKash User Manual')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">

		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="{{asset('vendor/glorifiedking/docs/css/style.css') }}">
  </head>
  <body>

		<div class="wrapper d-flex align-items-stretch">
			<nav id="sidebar">
				<div class="custom-menu">
					<button type="button" id="sidebarCollapse" class="btn btn-primary">
	          <i class="fa fa-bars"></i>
	          <span class="sr-only">Toggle Menu</span>
	        </button>
        </div>
				<div class="p-4 pt-5">
		  		<div class="col-md-12 sidebar-logo"><a href="#" class="logo"><img src="{{asset('vendor/glorifiedking/docs/images/temp_logo.png') }}"/></a></div>
	        <ul class="list-unstyled components mb-5">
	          <li class="{{ (url()->current() == route('bustravel.docs')) ? 'active' : '' }}">
              <a href="{{route('bustravel.docs')}}"> User account access</a>
	          </li>
	         
            <li class="{{ (url()->current() == route('bustravel.docs.buses')) ? 'active' : '' }}">
	              <a href="{{route('bustravel.docs.buses')}}">Buses</a>
	          </li>
            <li class="{{ (url()->current() == route('bustravel.docs.drivers')) ? 'active' : '' }}">
	              <a href="{{route('bustravel.docs.drivers')}}">Drivers</a>
	          </li>
	          <li class="{{ (url()->current() == route('bustravel.docs.routes')) ? 'active' : '' }}">
              <a href="{{route('bustravel.docs.routes')}}">Bus Routes</a>
	          </li>
            <li class="{{ (url()->current() == route('bustravel.docs.bookings')) ? 'active' : '' }}">
              <a href="{{route('bustravel.docs.bookings')}}">Bookings</a>
	        </li>
	        <li class="{{ (url()->current() == route('bustravel.docs.reports')) ? 'active' : '' }}">
              <a href="{{route('bustravel.docs.reports')}}">Reports</a>
	        </li>
	        <li class="{{ (url()->current() == route('bustravel.docs.settings')) ? 'active' : '' }}">
              <a href="{{route('bustravel.docs.settings')}}">Settings</a>
            </li>
            <li>
                <a href="{{route('bustravel.dashboard')}}">Back home</a>
              </li>
	        </ul>


	        <div class="footer">
	        	<p>
						  Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | <a href="https://palmkash.com" target="_blank">PalmKash</a>
				</p>
	        </div>

	      </div>
    	</nav>

        <!-- Page Content  -->
      <div id="content" class="p-4 p-md-5 pt-5">
          @yield('content')
       

        
      </div>
		</div>

    <script src="{{ asset('vendor/glorifiedking/docs/js/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/glorifiedking/docs/js/popper.js') }}"></script>
    <script src="{{ asset('vendor/glorifiedking/docs/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendor/glorifiedking/docs/js/main.js') }}"></script>
  </body>
</html>