@extends('layout.master')
@section('content')
<section class="dashboard-wrap">
   <div class="container">
      <div class="form-wrap login-signup-form">
         <header>
            <h2>Login</h2>
         </header>
         <form action="{{ route('user.login') }}" method="post">
            @csrf
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
            <div class="col-md-12">
               <div class="field">
                  <label class="form-label none">Login as:</label>
                  <div class="d-flex align-items-center">
                     <div class="form-check form-check-inline w-auto">
                        <input class="form-check-input" type="radio" name="role" id="buyer"
                           value="buyer" required="">
                        <label class="mb-0" for="buyer">Buyer</label>
                     </div>
                     <div class="form-check form-check-inline w-auto">
                        <input class="form-check-input" type="radio" name="role" id="seller"
                           value="seller" required="">
                        <label class="mb-0" for="seller">Seller</label>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-md-12">
               <div class="field">
                  <label for="email">Email address</label>
                  <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
               </div>
            </div>
            <div class="col-md-12">
               <div class="field">
                  <label for="password" class="form-label">Password</label>
                  <input type="password" class="form-control" id="password" name="password"  placeholder="Enter your password" required>
               </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
               <button type="submit" class="cta-primary med with-shadow">Login</button>
               <a href="{{ route('user.password.request') }}" class="forgotPass">Forgot
               password?</a>
            </div>
         </form>
         <p class="text-center mt-3">
            Don't have an account? <a href="{{ route('user.register') }}"  class="forgotPass">Sign up</a>
         </p>
      </div>
   </div>
</section>
@endsection