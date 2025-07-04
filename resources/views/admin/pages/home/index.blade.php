@extends('admin.layout.master')

@section('content')

<style> 


</style>
<div class="row">
  <div class="col-12 grid-margin">
    <div class="card">
      <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-3">
          <h3>Home Banners</h3>
          <a href="{{ route('admin.banner.create') }}" class="btn btn-primary">+ Add Banner</a>
        </div>

        @if(session('message'))
        <div class="alert alert-success">
          <div>{{ session('message') }}</div>
        </div>
        @endif

        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Sno</th>
                <th>Banner</th>
                <th>Title</th>
                <th>Short Description</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($banners as $index => $banner)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                  @if($banner->image)
                  <a href="{{ asset('uploads/banners/' . $banner->image) }}" target="_blank">
                    <img src="{{ asset('uploads/banners/' . $banner->image) }}" alt="Banner">

                    </a>
                  @else
                    N/A
                  @endif
                </td>
                <td>{{ $banner->title }}</td>
                <td>{{ $banner->short_description }}</td>
                <td>
                  <form action="{{ route('admin.banner.destroy', $banner->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this banner?');" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                  </form>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="5" class="text-center">No banners found.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
</div>
@stop
