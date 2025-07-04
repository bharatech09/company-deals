@extends('admin.layout.master')
@section('content')
<div class="row ">
  <div class="col-12 grid-margin">
    <div class="card">
      <div class="card-body">
        <h3>Users</h3>
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
                <th>Email verified?</th>
                <th>Phone</th>
                <th>Phone verified?</th>
                <th> Edit </th>
                <th> Delete </th>
              </tr>
            </thead>
            <tbody>
              @foreach ($users as $user)

              <tr>
                <td> {{ $user->name }} </td>
                <td> {{ $user->email }}</td>
                <td class="text-center"> 
                  @if($user->email_verified == 1)
                    <span class="mdi mdi-check"></span>
                  @else
                    <a href="{{ route('admin.user.verify_email', $user->id) }}">Verify it.</a>
                  @endif
                  </td>
                <td> {{ $user->phone }}</td>
                <td class="text-center">
                  @if($user->phone_verified == 1)
                    <span class="mdi mdi-check"></span>
                  @else
                    <a href="{{ route('admin.user.verify_phone', $user->id) }}">Verify it.</a>
                  @endif

                </td>
                <td><a href="{{ route('admin.useredit', $user->id) }}"><i class="mdi mdi-pencil"></i></a> </td>
                <td><a   href="{{ route('admin.userdelete', $user->id) }}"><i class="mdi mdi-delete"></i></a></td>    
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