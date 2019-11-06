<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>@yield('title', config('adminlte.title', 'PalmKash'))</title>
  <link href="{{ asset('vendor/glorifiedking/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
  <link href="{{ asset('vendor/glorifiedking/css/app.css') }}" rel="stylesheet" type="text/css" />
</head>
<body>
  <div class="container">
    @yield('content')
  </div>
  <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>	
  <script src="{{ asset('vendor/glorifiedking/js/app.js') }}" type="text/js"></script>
</body>
</html>

