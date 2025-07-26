@extends('admin.layout.master')
@section('content')
<div class="row ">
  <div class="col-12 grid-margin">
    <div class="card">
      <div class="card-body">
        <h3>NOC Trademarks</h3>

        @if(session('message'))
      <div class="alert alert-success">
        <div> {{ session('message') }}</div>
      </div>
    @endif

        <div class="table-responsive">
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>@sortablelink('urn', 'URN')</th>
                <th>Trademarks Detail</th>
                <th>@sortablelink('proprietor', 'Proprietor')</th>
                <th>Description</th>
                <th>@sortablelink('ask_price', 'Ask Price')</th>
                <th>@sortablelink('is_active', 'Is Active?')</th>
                <th>@sortablelink('approved', 'Admin Approval')</th>
                <th>Payment</th>
                <th>View</th>
                <th>Seller Name</th>
                <th>Seller Email</th>
                <th>Seller Contact</th>
                <th>@sortablelink('created_at', 'Date & Time stamp')</th>
                <th>Delete</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($nocTrademarks as $trademark)
            <tr>
            <td>{{ $trademark->urn }}</td>
            <td>
              Word Mark: {{ $trademark->wordmark }}<br>
              Application Number: {{ $trademark->application_no }}<br>
              Class: {{ $trademark->class_no }}<br>
              Status: {{ $trademark->status }}<br>
              Valid Upto: {{ date('Y-m-d', strtotime($trademark->valid_upto)) }}
            </td>
            <td class="text-center">{{ $trademark->proprietor }}</td>
            <td class="text-center">{{ $trademark->description }}</td>
            <td class="text-center">{{ $trademark->ask_price }} {{ $trademark->ask_price_unit }} per month</td>
            <td class="text-center">{{ $trademark->is_active }}</td>
            <td>
              <form action="{{ route('admin.trademark.toggleApproval', $trademark->id) }}" method="POST">
              @csrf
              @if ($trademark->approved == 1)
          <button type="submit" class="btn btn-sm btn-danger">Disapprove</button>
          <span class="badge badge-success mt-1 d-block">Approved</span>
          @else
          <button type="submit" class="btn btn-sm btn-success">Approve</button>
          <span class="badge badge-danger mt-1 d-block">Not Approved</span>
          @endif
              </form>
            </td>
            <td>
              <a
              href="{{ route('admin.trademark.payment', ['service_id' => $trademark->id, 'service_type' => 'seller_trademark']) }}">
              Seller Payment
              </a>
            </td>
            <td>
              <a href="{{ route('admin.trademark.detail', $trademark->id) }}">
              <i class="mdi mdi-eye-outline"></i>
              </a>
            </td>
            <td>
              <p>{{ $trademark->user->name ?? '' }}</p>
            </td>
            <td>
              <p>{{ $trademark->user->email ?? '' }}</p>
            </td>
            <td>
              <p>{{ $trademark->user->phone ?? '' }}</p>
            </td>
            <td class="text-center">{{ $trademark->created_at->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}
            </td>
            <td>
              <form action="{{ route('admin.trademark.destroy', $trademark->id) }}" method="POST"
              onsubmit="return confirm('Are you sure you want to delete this trademark?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-danger"><i class="mdi mdi-delete"></i></button>
              </form>
            </td>
            </tr>
        @endforeach
            </tbody>
          </table>

          {{-- Pagination --}}
          <div class="mt-3">
            {!! $nocTrademarks->appends(request()->except('page'))->links() !!}
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
@stop