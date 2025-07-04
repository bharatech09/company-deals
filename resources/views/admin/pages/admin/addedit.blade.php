@extends('admin.layout.master')
@section('content')
<div class="row">
  <div class="col-md-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Add a new admin</h4>
        @if($errors->any())
        	<div class="alert alert-danger">
		    @foreach ($errors->all() as $error)
		        <div>{{ $error }}</div>
		    @endforeach
			</div>
		@endif
        <form action="{{ $admin->exists ? route('admin.adminupdate', $admin->id) : route('admin.adminsave') }}" method="POST">
		    @csrf
		    @if ($admin->exists)
		        @method('PUT')
		    @endif
          <div class="form-group">
            <label for="name">Name</label>
            
            <input type="text" name="name"  class="form-control" id="name" placeholder="Name" value="{{ old('name', $admin->name) }}" required>
          </div>
          <div class="form-group">
            <label for="email">Email address</label>
           @if ($admin->exists)
              <p>{{$admin->email}}</p>
            @else
            <input type="text" name="email"  class="form-control" id="email" placeholder="Email" value="" required>
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