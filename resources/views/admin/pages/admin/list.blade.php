@extends('admin.layout.master')
@section('content')
<div class="row ">
  <div class="col-12 grid-margin">
    <div class="card">
      <div class="card-body">
        <h3>Admins</h3><span class="float-right"><a class="btn btn-primary"  href="{{route('admin.adminadd')}}"><i class="fa fa-plus"></i> Add A New Admin</a></span>
        @if(session('message'))
        <div class="alert alert-success">
              <div> {{ session('message') }}</div>
        </div>
        @endif
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                
                <th>Name</th>
                <th>Email</th>
                <th> Edit </th>
                <th> Delete </th>
              </tr>
            </thead>
            <tbody>
              @foreach ($admins as $admin)

              <tr>
                <td> {{ $admin->name }} </td>
                <td> {{ $admin->email }}</td>
                <td><a href="{{ route('admin.adminedit', $admin->id) }}"><i class="mdi mdi-pencil"></i></a> </td>
                <td><a   href="{{ route('admin.admindelete', $admin->id) }}"><i class="mdi mdi-delete"></i></a></td>    
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