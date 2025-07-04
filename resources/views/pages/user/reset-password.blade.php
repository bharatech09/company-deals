@extends('layout.master')
@section('content')
<section class="dashboard-wrap">
   <div class="container">
      <div class="form-wrap login-signup-form">
        <header>
            <h2>Reset your password</h2>
         </header>
    		<form action="{{ route('user.password.update') }}" method="POST">
          @csrf
          <input type="hidden" name="token" value="{{ $token }}" />
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
                  <label for="email">Enter your Email</label>
                  <input type="email" name="email" id="email"  class="form-control" required placeholder="Enter your Email">
               </div>
            </div>
            <div class="col-md-12">
               <div class="field">
                  <label for="password">New password</label>
                  <input type="password" name="password" id="password"  class="form-control" required placeholder="New password">
               </div>
            </div>
            <div class="col-md-12">
               <div class="field">
                  <label for="password_confirmation">Confirm password</label>
                  <input type="password" name="password_confirmation"  class="form-control" id="password_confirmation" required placeholder="Confirm password">
               </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
               <button type="submit" class="cta-primary med with-shadow">Reset Password</button>
            </div>
      </form>     
      </div>
   </div>
</section>
@endsection