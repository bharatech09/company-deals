@extends('layout.master')
@section('content')
<section class="dashboard-wrap">
    <div class="container">
        <div class="form-wrap login-signup-form">
            <header>
                <h2>Email and WhatsApp Verification</h2>
            </header>
            @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
            @endif
            @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
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
                    <h3>Email Verification</h3>
                    @if($user->email_verified == 1)
                    <div class="alert alert-success">
                        <i class="mdi mdi-check-circle"></i> Your email has been verified successfully.
                    </div>
                    @else
                    <div class="alert alert-warning">
                        <p>Your email address ({{ $user->email }}) is not verified yet.</p>
                        <p>Please check your email for the verification link. If you haven't received it, you can request a new one:</p>
                        <p>
                            <b> Please note that your WhatsApp number is currently under review. Our admin team will verify it within the next 12 hours.</b>
                        </p>
                        <p><b>You will receive a confirmation once the verification is complete.</b></p>
                        <a href="{{ route('user.send_verify_email', $user->id) }}" class="cta-primary med with-shadow">
                            <i class="mdi mdi-email"></i> Resend Verification Email
                        </a>
                    </div>
                    @endif
                </div>
            </div>



            @if($user->email_verified == 1 && $user->phone_verified == 1)
            <div class="col-md-12 text-center">
                <div class="alert alert-success">
                    <i class="mdi mdi-check-circle"></i> All verifications completed successfully!
                </div>
                <a href="{{ route('user.login') }}" class="cta-primary with-shadow">
                    <i class="mdi mdi-login"></i> Proceed to Login
                </a>
            </div>
            @endif
            <footer class="text-center mt-4 mb-4">
                <p class="text-muted">Team, CompanyDeals</p>
            </footer>

        </div>

    </div>
</section>

@endsection