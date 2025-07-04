@extends('admin.layout.master-mini')
@section('content')
<div class="content-wrapper d-flex align-items-center justify-content-center auth theme-one" style="background-image: url({{ url('admin/assets/images/auth/login_1.jpg') }}); background-size: cover;">
  <div class="row w-100">
    <div class="col-lg-4 mx-auto">
      <div class="auto-form-wrapper">
        <form action="{{ route('admin.password.email') }}" method="POST">
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
          <div class="form-group">
            <label class="label">Enter your Email</label>
            <div class="input-group">
              <input type="email" name="email"  class="form-control" required placeholder="Enter your Email">
              <div class="input-group-append">
                <span class="input-group-text">
                  <i class="mdi mdi-check-circle-outline"></i>
                </span>
              </div>
            </div>
          </div>
          <div class="form-group">
            <button class="btn btn-primary submit-btn btn-block">Send Password Reset Link</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection