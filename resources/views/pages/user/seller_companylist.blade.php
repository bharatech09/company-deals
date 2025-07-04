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
                        <h2>Your Companies</h2>
                    </header>
                </div>
                <div class="col-md-6">
                    <a class="cta-primary float-end" href="{{ route('user.seller.companyform.showstep1')}}" >Add Company</a>
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
            @foreach ($arrCompany as $company)
            <div class="col-md-6">
                     @include('partials.seller_company', ['company' => $company])
                </div>
            @endforeach
        </div>   
    </div>
</div>
</div>
</div>
</div>
</section>
@endsection


