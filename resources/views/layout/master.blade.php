<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="icon" href="images/favicon.png" type="image/x-icon" />
  <link rel="shortcut icon" href="{{asset('images/favicon.ico')}}" type="image/x-icon" />
  <meta name="_token" content="{{ csrf_token() }}">
  <!-- CSS -->
  <link rel="stylesheet" href="{{asset('frontendassets/css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{asset('frontendassets/css/fontawesome-6.6-all.css')}}">
  <link rel="stylesheet" href="{{asset('frontendassets/css/company-deals.css')}}">
  <!-- font-family: "Raleway", serif; | font-family: "Open Sans", serif; -->
  <link
    href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap"
    rel="stylesheet">
  <title>Company Deals</title>
  @stack('plugin-styles')
</head>
<body class="">
  <div id="window">
    @include('layout.header')
    <main class="body-wrap">
      @yield('content')
    </main>
    @include('layout.footer')
  </div>
  <script src="{{asset('frontendassets/js/jquery-3.7.1.min.js')}}"></script>
  <script src="{{asset('frontendassets/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('frontendassets/js/flickity.min.js')}}"></script>
  <script src="{{asset('frontendassets/js/function.js?v=1')}}"></script>
  @stack('plugin-scripts')
</body>
</html>