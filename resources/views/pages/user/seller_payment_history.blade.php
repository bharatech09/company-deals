@extends('layout.master')
@section('content')
<section class="dashboard-wrap">
    <div class="container">
        <div class="row">


            @include('layout.seller_nav')

            <div class="col-lg-8 col-xl-9">
                <div class="dashboard-details">
                    <header>
                        <h2>Payment History</h2>
                    </header>
                    @if(session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                    @endif
                    @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <!-- Filter/Search Form -->
                    <form method="GET" class="row g-3 mb-4">
                        <div class="col-md-3">
                            <input type="date" name="from" class="form-control" value="{{ request('from') }}" placeholder="From date">
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="to" class="form-control" value="{{ request('to') }}" placeholder="To date">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="PAID" {{ request('status') == 'PAID' ? 'selected' : '' }}>Paid</option>
                                <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>Pending</option>
                                <option value="FAILED" {{ request('status') == 'FAILED' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Amount (INR)</th>
                                    <th>Status</th>
                                    <th>Transaction ID</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $payment)
                                <tr>
                                    <td>{{ $payment->created_at->format('d-m-Y H:i') }}</td>
                                    <td>{{ $payment->amount }}</td>
                                    <td>{{ ucfirst($payment->status) }}</td>
                                    <td>{{ $payment->transaction_id ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No payment history found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection