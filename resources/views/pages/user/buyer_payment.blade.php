@extends('layout.master')
@section('content')
<section class="dashboard-wrap">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-xl-5">
                <div class="dashboard-details">
                    <header>
                        <h2>Pay to View Seller Details</h2>
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
                    <form method="POST" action="{{ route('user.buyer.pay.process') }}">
                        @csrf
                        <input type="hidden" name="type" value="{{ $type ?? 'global' }}">
                        <input type="hidden" name="item_id" value="{{ $id ?? '' }}">
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount (INR)</label>
                            <input type="number" class="form-control" id="amount" name="amount" value="2000" readonly required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Pay with Cashfree</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 