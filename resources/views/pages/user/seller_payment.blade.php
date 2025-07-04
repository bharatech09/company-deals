@extends('layout.master')
@section('content')
<section class="dashboard-wrap">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-xl-5">
                <div class="dashboard-details">
                    <header>
                        <h2>Seller Payment</h2>
                    </header>
                    <form method="POST" action="{{ route('user.seller.payment.process') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount (INR)</label>
                            <input type="number" class="form-control" id="amount" name="amount" min="1" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Pay with Cashfree</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 