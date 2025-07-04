@extends('admin.layout.master')
@section('content')
<div class="row">
  <div class="col-12 grid-margin">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Make payment</h4>
        @if($errors->any())
        	<div class="alert alert-danger">
		    @foreach ($errors->all() as $error)
		        <div>{{ $error }}</div>
		    @endforeach
			</div>
		@endif
        <form action="{{ route('admin.trademark.paymentsave') }}" method="POST">
          <input type="hidden" name="service_type" value="{{$service_type}}">
          <input type="hidden" name="service_id" value="{{$service_id}}">
		    @csrf
		    @method('PUT')

          <div class="form-group">
            <label for="amount">Amount</label>
            
            <input type="text" name="amount"  class="form-control" id="amount" placeholder="Amount" value="" required>
          </div>
          <div class="form-group">
            <label for="service_start_date" data-provide="datepicker">Start Date</label>
            
            <input type="text" name="service_start_date"  class="form-control datepicker" id="service_start_date" placeholder="Start Date" value="" required>
          </div>
          <div class="form-group">
            <label for="service_end_date">End Date</label>
            
            <input type="text" name="service_end_date"  class="form-control datepicker" id="service_end_date" placeholder="End Date" value="" required>
          </div>
          <button type="submit" class="btn btn-primary me-2">Submit</button>
          <button class="btn btn-dark">Cancel</button>
        </form>
      </div>
    </div>
  </div>
</div>

@push('plugin-styles')
    <link rel="stylesheet" href="{{asset('adminassets/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}">
@endpush
@push('plugin-scripts')
    <script src="{{asset('adminassets/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script type="text/javascript">
      $('.datepicker').datepicker({
    format: 'yyyy-mm-dd'});
    </script>
@endpush
@stop

    
