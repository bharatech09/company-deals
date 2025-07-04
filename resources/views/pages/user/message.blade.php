@extends('layout.master')
@section('content')
    <section class="dashboard-wrap">
        <div class="container">
            <div class="row">
                @include('layout.seller_nav')

                <div class="col-md-9">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sno</th>
                                    <th>From</th>
                                    <th>Message</th>
                                    <th>Message At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($message as $index => $msg)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>Company Deals</td>
                                        <td>{{ $msg->message }}</td>
                                        <td>{{ $msg->created_at->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}</td>
                                    </tr>
                                @endforeach

                                @if ($message->isEmpty())
                                    <tr>
                                        <td colspan="3" class="text-center">No messages found.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
