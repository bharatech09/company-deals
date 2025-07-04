@extends('layout.master')
@section('content')
<section class="dashboard-wrap">
<div class="container">
    <div class="row">
        @include('layout.seller_nav')
        <div class="col-lg-8 col-xl-9">
            <div class="dashboard-details">
            <button class="navToggle2 cta-primary mb-4"><i class="fa-solid fa-sliders"></i> Open Dashboard Nav</button>
            
                <div class="row">
                <div class="col-md-6">
                   <header>
                    <h2>Your Assignments</h2>
                </header>
                </div>
                <div class="col-md-6">
                    <a  class="cta-primary float-end"  href="{{ route('user.seller.addassignment')}}">
                        Add Assignment
                    </a>
                </div>
            </div>

            
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif 
            
        
        <div class="buyer-seller-details">
            <div class="row">  
            @foreach ($arrAssignment as $assignment)
             <div class="col-md-6">
                @include('partials.seller_assignment', ['assignment' => $assignment])
            </div>
            @endforeach
        </div>   
    </div>
</div>
</div>
</div>
</div>
</section>
@push('plugin-styles')
    <link rel="stylesheet" href="{{asset('adminassets/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}">
@endpush
@push('plugin-scripts')
    <script src="{{asset('adminassets/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script type="text/javascript">
      $('.datepicker').datepicker({
    format: 'yyyy-mm-dd', startDate: '-0m'});
    </script>
@endpush
@endsection