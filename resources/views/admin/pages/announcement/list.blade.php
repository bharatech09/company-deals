@extends('admin.layout.master')
@section('content')
<div class="row ">
  <div class="col-12 grid-margin">
    <div class="card">
      <div class="card-body">
        <h3>Announcement</h3><span class="float-right"><a class="btn btn-primary"  href="{{route('admin.announcement.add')}}"><i class="fa fa-plus"></i> Add A New Announcement</a></span>
        @if(session('message'))
        <div class="alert alert-success">
              <div> {{ session('message') }}</div>
        </div>
        @endif
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Title</th>
                <th>Date</th>
                <th>Description</th>
                <th>Status</th>
                <th> Edit </th>
                <th> Delete </th>
              </tr>
            </thead>
            <tbody>
              
              @foreach ($announcements as $announcement)
              <tr>
                <td>{{$announcement->title}}</td>
                <td>{{date_format(date_create($announcement->announcement_date),"d F Y")}}</td>
                <td>{{$announcement->description }}</td>
                <td>{{$announcement->status}}</td>
                <td><a href="{{ route('admin.announcement.edit', $announcement->id) }}"><i class="mdi mdi-pencil"></i></a></td>
                <td><a href="{{ route('admin.announcement.delete', $announcement->id) }}"><i class="mdi mdi-delete"></i></a></td>    
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