@extends('layout.master')
@section('content')
<section class="dashboard-wrap">
<div class="container">
    <div class="row">
        @if (session('role') == 'seller')
            @include('layout.seller_nav')
        @elseif (session('role') == 'buyer')
           @include('layout.buyer_nav')
        @endif
        <div class="col-lg-8 col-xl-9">
            <div class="dashboard-details">
            <button class="navToggle2 cta-primary mb-4"><i class="fa-solid fa-sliders"></i> Open Dashboard Nav</button>
            <div class="form-wrap">
            <header>
            <h2>Your Details</h2>
            </header>
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
             <div class="row">
                    <div class="col-md-6">
                        <div class="field">
                            Name: {{ auth()->guard('user')->user()->name }}
                            
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="field">
                             WhatsApp No: {{ auth()->guard('user')->user()->phone }}
                            
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="field">
                             Email: {{ auth()->guard('user')->user()->email }}
                            
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="field">

                             Previous deals done: 
                             @if (session('role') == 'seller')
                                {{ auth()->guard('user')->user()->no_deal_closed }}
                             @elseif (session('role') == 'buyer')
                                {{ auth()->guard('user')->user()->buyer_no_deal_closed }}
                             @endif
                            
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="field">
                             Amount of deals closed: 
                             @if (session('role') == 'seller')
                                {{ number_format((auth()->guard('user')->user()->amount_deal_closed/1000),2) }} 
                             @elseif (session('role') == 'buyer')
                                {{ number_format((auth()->guard('user')->user()->buyer_amount_deal_closed/1000),2) }} 
                             @endif

                             Thousands
                            
                        </div>
                    </div>
                    
            </div>
            <form action="{{ route('user.change-password') }}" method="post">
                @csrf
                
            <header>
                <h2>Change your password</h2>
            </header>
                <div class="row">
                    <div class="col-md-12">
                        <div class="field">
                            <label for="current_password">Current password</label>
                            <input type="password" name="current_password" id="current_password" class="form-control" placeholder="Current password">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="field">
                            <label for="new_password">New password</label>
                            <input type="password" name="new_password" id="new_password" class="form-control" placeholder="New password">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="field">
                            <label for="new_password_confirmation">Confirm new Password</label>
                            <input type="password" name="new_password_confirmation" class="form-control" id="new_password_confirmation" placeholder="Confirm new Password">
                        </div>
                    </div>
                <div class="col-xl-12">
                    <button class="cta-primary" type="submit">Change Password</button>
                </div>
               </div>
            </form>
        </div>   
    </div>
</div>
</div>
</div>
</section>

@endsection