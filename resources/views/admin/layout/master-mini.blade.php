<!DOCTYPE html>
<html>
<head>
  <title>Company Deals</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- CSRF Token -->
  <meta name="_token" content="{{ csrf_token() }}">
  
  <link rel="shortcut icon" href="{{ asset('/favicon.ico') }}">

  <!-- plugin css -->
  <link rel="stylesheet" href="{{asset('adminassets/assets/plugins/@mdi/font/css/materialdesignicons.min.css')}}">
  <link rel="stylesheet" href="{{asset('adminassets/assets/plugins/perfect-scrollbar/perfect-scrollbar.css')}}">
  <!-- end plugin css -->
  <!-- common css -->
  <link rel="stylesheet" href="{{asset('adminassets/css/app.css')}}">
  
  <!-- end common css -->
</head>
<body data-base-url="{{url('/')}}">

  <div class="container-scroller" id="app">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      @yield('content')
    </div>
  </div>

    <!-- base js -->
    <script src="{{asset('adminassets/js/app.js')}}"></script>
    <!-- end base js -->
</body>
</html>