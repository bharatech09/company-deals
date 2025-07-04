@extends('admin.layout.master')
@section('content')
<div class="row">
  <div class="col-md-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Add a new Announcement</h4>
        @if($errors->any())
        	<div class="alert alert-danger">
		    @foreach ($errors->all() as $error)
		        <div>{{ $error }}</div>
		    @endforeach
			</div>
		@endif
        <form action="{{ route('admin.announcement.save') }}" method="POST" enctype="multipart/form-data">
		    @csrf
          <div class="form-group">
            <label for="title">Title</label>
            
            <input type="text" name="title"  class="form-control" id="title" placeholder="Title" value="{{ old('title') }}" required>
          </div>
      
          <div class="form-group">
            <label for="announcement_date">Date</label>
            <input type="text" name="announcement_date"  class="form-control datepicker" id="announcement_date" placeholder="Date" value="{{ old('announcement_date') }}" required>
           
          </div>
          <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description"  class="form-control" id="description" placeholder="Description">{{ old('description') }}</textarea>
            
          </div>
          <div class="form-group">
            <label for="status">Status</label>
            <select id="status" class="form-control" name="status" required="">
                <option value="">--Select--</option>
                <option value="inactive" {{old('status') == 'inactive' ? "selected" : "" }}>Inactive</option>
                <option value="active" {{old('status') == 'active' ? "selected" : "" }}>Active</option>
            </select>
            
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