@extends('admin.layout.master')
@section('content')
<div class="row">
  <div class="col-md-12 grid-margin">
    <div class="card">
      <div class="card-body">
        <div class="col-lg-4 mx-auto">
      <h2 class="text-center mb-4">Change Password</h2>
      <div class="auto-form-wrapper">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
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
        <form action="{{ route('admin.change-password') }}" method="POST">
          @csrf
          <div class="form-group">
            <div class="input-group">
              <input type="password" name="current_password" class="form-control" placeholder="Current password">
              <div class="input-group-append">
                <span class="input-group-text">
                  <i class="mdi mdi-check-circle-outline"></i>
                </span>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="input-group">
              <input type="password" name="new_password" class="form-control" placeholder="New password">
              <div class="input-group-append">
                <span class="input-group-text">
                  <i class="mdi mdi-check-circle-outline"></i>
                </span>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="input-group">
              <input type="password" name="new_password_confirmation" class="form-control" placeholder="Confirm new Password">
              <div class="input-group-append">
                <span class="input-group-text">
                  <i class="mdi mdi-check-circle-outline"></i>
                </span>
              </div>
            </div>
          </div>
          <div class="form-group">
            <button class="btn btn-primary submit-btn btn-block">Change Password</button>
          </div>
        </form>
      </div>
    </div>
      </div>
    </div>
</div>
</div>
@endsection
