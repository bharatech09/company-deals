@extends('admin.layout.master')

@section('content')

    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3>Messages</h3>
                        <a href="{{ route('pages.messages.add') }}" class="btn btn-primary">+ Add New Message</a>
                    </div>

                    @if (session('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sno</th>
                                    <th>Name</th>
                                    <th>Message</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($messages as $index => $message)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ \App\Models\User::find($message->user_id)->name ?? 'Unknown' }}</td>

                                        <td>{{ $message->message }}</td>

                                        <td>
                                            <form action="{{ route('pages.messages.destroy', $message->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this message?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </td>
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
