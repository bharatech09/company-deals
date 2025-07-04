<!DOCTYPE html>
<html>
<head>
  <title></title>
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

  @stack('plugin-styles')

  <!-- common css -->
  <link rel="stylesheet" href="{{asset('adminassets/css/app.css')}}">
  <!-- end common css -->

  @stack('style')
</head>
<body data-base-url="{{url('/')}}">

  <div class="container-scroller" id="app">
    @include('admin.layout.header')
    <div class="container-fluid page-body-wrapper">
      @include('admin.layout.sidebar')
      <div class="main-panel">
        <div class="content-wrapper">
          @yield('content')
        </div>
        @include('admin.layout.footer')
      </div>
    </div>
  </div>

  <!-- base js -->
  <script src="{{asset('adminassets/js/app.js')}}"></script>
  <script src="{{asset('adminassets/assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
  <!-- end base js -->

  <!-- plugin js -->
  @stack('plugin-scripts')
  <!-- end plugin js -->

  <!-- common js -->
  <script src="{{asset('adminassets/assets/js/off-canvas.js')}}"></script>
  <script src="{{asset('adminassets/assets/js/hoverable-collapse.js')}}"></script>
   <script src="{{asset('adminassets/assets/js/misc.js')}}"></script>
   <script src="{{asset('adminassets/assets/js/misc.js')}}"></script>
   <script src="{{asset('adminassets/assets/js/settings.js')}}"></script>
   <script src="{{asset('adminassets/assets/js/todolist.js')}}"></script>
  <!-- end common js -->
  
</body>
</html>