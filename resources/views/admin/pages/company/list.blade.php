@extends('admin.layout.master')
@section('content')
<div class="row ">
  <div class="col-12 grid-margin">
    <div class="card">
      <div class="card-body">
        <h3>Company Lists</h3>

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
                <th>@sortablelink('name', 'Name')</th>
                <th>@sortablelink('ask_price', 'Ask Price')</th>
                <th>@sortablelink('status', 'Is Active?')</th>
                <th>@sortablelink('deal_closed', 'Deal Closed')</th>
                <th>@sortablelink('home_featured', 'Featured')</th>
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
              @foreach ($companys as $company)
              <tr>
                <td>{{ $company->urn }}</td>
                <td class="text-center">{{ $company->name }}</td>
                <td class="text-center">{{ $company->ask_price }} {{ $company->ask_price_unit }}</td>
                <td class="text-center">{{ $company->status }}</td>
                <td class="text-center">{{ $company->deal_closed ? 'Yes' : 'No' }}</td>
                <td class="text-center">{{ $company->home_featured ? 'Yes' : 'No' }}</td>
                <td>
                  <a href="{{ route('admin.company.payment', ['service_id'=>$company->id,'service_type'=>'seller_company']) }}">Seller Payment</a>
                </td>
                <td>
                  <a href="{{ route('admin.company.detail', $company->id) }}"><i class="mdi mdi-eye-outline"></i></a>
                </td>
                <td>
                  <p>{{ $company->user->name ?? '' }}</p>
                </td>
                <td>
                  <p>{{ $company->user->email ?? '' }}</p>
                </td>
                <td>
                  <p>{{ $company->user->phone ?? '' }}</p>
                </td>
                <td class="text-center">{{ $company->created_at->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}</td>
                <td>
                  <form method="POST" action="{{ route('admin.company.destroy', $company->id) }}" onsubmit="return confirm('Are you sure you want to delete this company?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger"><i class="mdi mdi-delete"></i></button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>

          {{-- Pagination with sort params --}}
          <div class="mt-3">
            {!! $companys->appends(request()->except('page'))->links() !!}
          </div>

        </div>

      </div>
    </div>
  </div>
</div>
@stop
