<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="msapplication-TileColor" content="#0F1624">
  <meta name="theme-color" content="#0F1624">
  <title>@yield('title', config('adminlte.title', 'PalmKash Bus Ticketing System'))</title>
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('vendor/glorifiedking/fav/apple-touch-icon.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('vendor/glorifiedking/fav/favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('vendor/glorifiedking/fav/favicon-16x16.png') }}">
  <link rel="shortcut icon" href="{{ asset('fav/favicon.png') }}">
  <link href="{{ asset('vendor/glorifiedking/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
  <link href="{{ asset('vendor/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" type="text/css" href="{{ asset('vendor/glorifiedking/css/base.css')}}">
  <link rel="stylesheet" type="text/css" href="{{ asset('vendor/glorifiedking/css/styles.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('vendor/glorifiedking/css/queries.css') }}">
  <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">

  
</head>
<body>
    <div class="header-title">
        <h1>@yield('page-heading',config('adminlte.title', ''))</h1>
    </div>
    @include('bustravel::frontend.partials.header')
    <main>
      <div class="container-fluid">
        @yield('content')
      </div>
    </main>
    <svg id="all-svg" width="0" height="0"></svg>
  <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>  
  <script src="{{ asset('vendor/select2/select2.min.js') }}"></script> 
  <script type="text/javascript" src="{{ asset('vendor/glorifiedking/js/modnz.js') }}"></script>
  <script type="text/javascript" src="{{ asset('vendor/glorifiedking/js/aos.js') }}"></script>  
  <script type="text/javascript" src="{{ asset('vendor/glorifiedking/js/scripts.js') }}"></script>
  @yield('js')
</body>
</html>

