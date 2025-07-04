@extends('admin.layout.master')
@section('content')
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">

                    @if (\Session::has('success'))
                        <div class="alert alert-success">
                            <ul>
                                <li>{!! \Session::get('success') !!}</li>
                            </ul>
                        </div>
                    @endif

                    <h3 class="mb-4">Add New Messages</h3>
                    <form action="{{ route('pages.messages.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Select Buyer / Seller</label>
                            <select class="form-control" id="name" style="padding: 0 !important;" multiple name="user_id[]"
                                required>
                                <option value="" selected>-- Select Buyer / Seller --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>





                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="4" placeholder="Write your message..."
                                required></textarea>
                        </div>


                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>


                    <hr class="my-4">


                </div>
            </div>
        </div>

    </div>
@endsection()
