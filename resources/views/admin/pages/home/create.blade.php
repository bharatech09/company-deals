@extends('admin.layout.master')
@section('content')
<div class="row">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">

                @if(session('message'))
        <div class="alert alert-success">
          <div>{{ session('message') }}</div>
        </div>
        @endif
                <h3 class="mb-4">Add New Banner</h3>
                <form action="{{ route('admin.banner.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="banner">Banner Image</label></br>
                        <span><b><i>Banner size should be  725 * 225 </i></b></span>
                        <input type="file" name="banner" id="banner" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" name="title" id="title" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="short_description">Short Description</label>
                        <textarea name="short_description" id="short_description" class="form-control" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-success mt-2">Save Banner</button>
                </form>

                <hr class="my-4">


            </div>
        </div>
    </div>

</div>

@endsection()
