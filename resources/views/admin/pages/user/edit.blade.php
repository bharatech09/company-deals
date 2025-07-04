@extends('admin.layout.master')
@section('content')
<div class="row">
  <div class="col-md-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">View user</h4>
        @if($errors->any())
          <div class="alert alert-danger">
    		    @foreach ($errors->all() as $error)
    		        <div>{{ $error }}</div>
    		    @endforeach
  			 </div>
		    @endif
        <form action="{{ route('admin.userupdate', $user->id) }}" method="POST">
		    @csrf
		    @method('PUT')
          <div class="form-group">
            <label for="name">Name</label>
            
            <input type="text" name="name"  class="form-control" id="name" placeholder="Name" value="{{ $user->name }}" required>
          </div>
          <div class="form-group" >
            <label for="phone">Phone</label>
            <div class="form-inline">
            <input type="text" name="phone"  class="form-control w-50" id="phone" placeholder="Name" value="{{ $user->phone }}" required>   
            @if($user->phone_verified == 1)
                    <span class="mdi mdi-check"></span>
            @else
              <a class="inline" href="{{ route('admin.user.verify_phone', $user->id) }}"> Verify it.</a>
            @endif
            </div>
          </div>
          <div class="form-group">
            <label for="name">Email: </label> {{ $user->email }} @if($user->email_verified == 1)
                    <span class="mdi mdi-check"></span>
                  @else
                    <a href="{{ route('admin.user.verify_email', $user->id) }}">Verify it.</a>
                  @endif
          </div>

          <div class="form-group">
            <label for="password">Password</label>
            <input type="text" name="password"  class="form-control" id="password" placeholder="Password" value="" >
          </div>
          <button type="submit" class="btn btn-primary me-2">Submit</button>
          <button class="btn btn-dark">Cancel</button>
        </form>
      </div>
    </div>
  </div>
</div>
@stop