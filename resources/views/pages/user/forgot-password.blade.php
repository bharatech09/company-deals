@extends('layout.master')
@section('content')
<section class="dashboard-wrap">
   <div class="container">
      <div class="form-wrap login-signup-form">
        <header>
            <h2>Reset Password</h2>
         </header>
    		<form action="{{ route('user.password.email') }}" method="POST">
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
                  <label for="email">Enter your Email</label>
                  <input type="email" name="email"  class="form-control" required placeholder="Enter your Email">
               </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
               <button type="submit" class="cta-primary med with-shadow">Send Password Reset Link</button>
            </div>
        </form>     
      </div>
    </div>
    </div>
</section>
@endsection