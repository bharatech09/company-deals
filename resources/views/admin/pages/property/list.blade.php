@extends('admin.layout.master')

@section('content')
<div class="row">
  <div class="col-12 grid-margin">
    <div class="card">
      <div class="card-body">
        <h3>Properties</h3>

        @if(session('message'))
      <div class="alert alert-success">
        {{ session('message') }}
      </div>
    @endif

        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>@sortablelink('urn', 'URN')</th>
                <th>Address</th>
                <th>@sortablelink('space', 'Space')</th>
                <th>@sortablelink('type', 'Type')</th>
                <th>@sortablelink('ask_price', 'Ask Price')</th>
                <th>@sortablelink('status', 'Status')</th>
                <th>@sortablelink('approved', 'Admin Approval')</th>
                <th>Payment</th>
                <th>View</th>
                <th>Seller Name</th>
                <th>Seller Email</th>
                <th>Seller Contact</th>
                <th>@sortablelink('created_at', 'Date & TimeStamp')</th>
                <th>Delete</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($properties as $property)
            <tr>
            <td>{{ $property->urn }}</td>
            <td>
              {{ $property->address }}<br>
              Pin: {{ $property->pincode }}<br>
              State: {{ $property->state }}
            </td>
            <td>{{ $property->space }} sq ft.</td>
            <td class="text-center">{{ $property->type }}</td>
            <td class="text-center">{{ $property->ask_price }} {{ $property->ask_price_unit }} per month</td>
            <td>{{ $property->status }}</td>
            <td>
              <form action="{{ route('admin.property.toggleApproval', $property->id) }}" method="POST">
              @csrf
              @if ($property->approved == 1)
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
              href="{{ route('admin.property.payment', ['service_id' => $property->id, 'service_type' => 'seller_property']) }}">
              Seller Payment
              </a>
            </td>
            <td>
              <a href="{{ route('admin.property.detail', $property->id) }}">
              <i class="mdi mdi-eye-outline"></i>
              </a>
            </td>

            <td>
                  <p>{{ $property->user->name ?? '' }}</p>
                </td>
                <td>
                  <p>{{ $property->user->email ?? '' }}</p>
                </td>
                <td>
                  <p>{{ $property->user->phone ?? '' }}</p>
                </td>
            <td class="text-center">{{ $property->created_at->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}
            </td>
            <td>
              <form method="POST" action="{{ route('admin.property.destroy', $property->id) }}"
              onsubmit="return confirm('Are you sure you want to delete this property?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-danger"><i class="mdi mdi-delete"></i></button>
              </form>
            </td>
            </tr>
        @endforeach
            </tbody>
          </table>

          <div class="mt-3">
            {!! $properties->appends(request()->except('page'))->links() !!}
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
@stop