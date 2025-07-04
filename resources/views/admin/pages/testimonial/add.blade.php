@extends('admin.layout.master')
@section('content')
<div class="row">
  <div class="col-md-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Add a new Testimonial</h4>
        @if($errors->any())
        	<div class="alert alert-danger">
		    @foreach ($errors->all() as $error)
		        <div>{{ $error }}</div>
		    @endforeach
			</div>
		@endif
        <form action="{{ route('testimonial.store.admin') }}" method="POST" enctype="multipart/form-data">
		    @csrf
          <div class="form-group">
            <label for="client_name">Client Name</label>
            
            <input type="text" name="client_name"  class="form-control" id="client_name" placeholder="Client Name" value="{{ old('client_name') }}" required>
          </div>
          <div class="form-group">
            <label for="client_image">Client Image</label>
            <input type="file" name="client_image"  class="form-control" id="client_image" placeholder="Client Image" value="{{ old('client_name') }}" required>
          </div>
          <div class="form-group">
            <label for="heading">Heading</label>
            <input type="text" name="heading"  class="form-control" id="heading" placeholder="Heading" value="{{ old('heading') }}" required>
           
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
@stop