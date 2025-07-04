@extends('admin.layout.master')
@section('content')
<div class="row ">
  <div class="col-12 grid-margin">
    <div class="card">
      <div class="card-body">
        <h3>Testimonials</h3><span class="float-right"><a class="btn btn-primary"  href="{{route('admin.testimonial.add')}}"><i class="fa fa-plus"></i> Add A New Testimonial</a></span>
        @if(session('message'))
        <div class="alert alert-success">
              <div> {{ session('message') }}</div>
        </div>
        @endif
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Client Name</th>
                <th>client image</th>
                <th>Heading</th>
                <th>Description</th>
                
                <th>Status</th>
                <th> Edit </th>
                <th> Delete </th>
              </tr>
            </thead>
            <tbody>
              
              @foreach ($testimonials as $testimonial)

              <tr>
                <td> {{ $testimonial->client_name }}</td>
                
                <td><img src="{{asset('storage/'.$testimonial->client_image)}}" width="100"></td>
                <td>{{ $testimonial->heading }}</td>
                <td>{{ $testimonial->description }}</td>
                <td>{{$testimonial->status}}</td>
                <td><a href="{{ route('admin.testimonial.edit', $testimonial->id) }}"><i class="mdi mdi-pencil"></i></a></td>
                <td><a href="{{ route('admin.testimonial.delete', $testimonial->id) }}"><i class="mdi mdi-delete"></i></a></td>    
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@stop