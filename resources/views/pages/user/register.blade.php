@extends('layout.master')
@section('content')
<section class="dashboard-wrap">
   <div class="container">
      <div class="form-wrap login-signup-form">
         <header>
            <h2>Register user</h2>
         </header>
         <form action="{{ route('user.register') }}" method="post">
            @csrf
            <input type="hidden" name="role" value="{{$ragisteras}}">
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
                  <label for="name">Name</label>
                  <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Name" required="">
               </div>
            </div>
            <div class="col-md-12">
               <div class="field">
                  <label for="email">Email</label>
                  <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email" required="">
               </div>
            </div>
            <div class="col-md-12">
               <div class="field">
                  <label for="phone">Whatsapp Number Only</label>
                  <input type="text" class="form-control" name="phone" value="{{ old('phone') }}" placeholder="Whatsapp Number Only" required="">
               </div>
            </div>
            <div class="col-md-12">
               <div class="field">
                  <label for="password">Password</label>
                  <input type="password" class="form-control" name="password" placeholder="Password" required="">
               </div>
            </div>
            <div class="col-md-12">
               <div class="field">
                  <label for="password_confirmation">Password confirmation</label>
                  <input type="password" class="form-control" name="password_confirmation" placeholder="Password confirmation" required="">
               </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
               <button type="submit" class="cta-primary med with-shadow">Register</button>
            </div>
         </form>
      </div>
   </div>
</section>
@endsection