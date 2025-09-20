@extends('layout.master')
@section('content')
@php
use App\Http\Controllers\Utils\GeneralUtils;
$selected_class = old('class_no');
$class_option = GeneralUtils::get_class_option($selected_class);
$ask_price_unit_option = Config::get('selectoptions.ask_price_unit_option');
$comman_type_option = Config::get('selectoptions.comman_type');
@endphp
<section class="dashboard-wrap">
    <div class="container">
        <div class="row">
            @include('layout.seller_nav')
            <div class="col-lg-8 col-xl-9">
                <div class="dashboard-details">
                    <button class="navToggle2 cta-primary mb-4"><i class="fa-solid fa-sliders"></i> Open Dashboard Nav</button>
                    <div class="form-wrap">
                        <header>
                            <h2>Submit the following form for NOC Trademark</h2>
                        </header>

                        @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                        @endif
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <form action="{{ route('user.seller.savetrademark') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="field">
                                        <label for="state">Word Mark</label>
                                        <b>Please do not put your email or mobile number in this field</b>

                                        <input id="wordmark" type="text" class="form-control" name="wordmark" placeholder="Word Mark" required="" value="{{ old('wordmark') }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="field">
                                        <label for="application_no">Application Number</label>
                                        <input id="application_no" maxlength="7" type="text" class="form-control" name="application_no" placeholder="Application Number" required="" value="{{ old('application_no') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field">
                                        <label for="class_no">Class</label>
                                        <select id="class_no" class="form-select" name="class_no" required="">
                                            <option value="">-Select-</option>
                                            {!!$class_option!!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="field">
                                        <label for="proprietor">Proprietor</label>
                                        <b>Please do not put your email or mobile number in this field</b>

                                        <input id="proprietor" type="text" class="form-control" name="proprietor" placeholder="Proprietor" required="" value="{{ old('proprietor') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field">
                                        <label for="status">Status</label>
                                        <select id="status" class="form-select" name="status" required="" value="{{ old('status') }}">
                                            <option value="">--Select--</option>
                                            <option value="VALID" {{ old("status") == 'VALID' ? "selected" : "" }}>VALID</option>
                                            <option value="PROTECTION GRANTED" {{ old("status") == 'PROTECTION GRANTED' ? "selected" : "" }}>PROTECTION GRANTED</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field">
                                        <label for="valid_upto">Valid Upto</label>
                                        <div class="controls bootstrap-timepicker flex">
                                            <input id="valid_upto" type="text" readonly class="datetime  form-control datepicker" name="valid_upto" placeholder="Valid Upto" value="{{ old('valid_upto') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="field">
                                        <label for="description">Description</label>
                                        <b>Please do not put your email or mobile number in this field</b>

                                        <textarea id="description" class="form-control" name="description" placeholder="Description" required="">{{ old('description') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="field">
                                        <label for="ask_price">Ask price</label>
                                        <input id="ask_price" type="number" class="form-control" name="ask_price" placeholder="Ask price" required="" value="{{ old('ask_price') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="field input-group">
                                        <label>&nbsp;</label>
                                        <select id="ask_price_unit" class="form-select" name="ask_price_unit" required="" value="{{ old('ask_price_unit') }}" aria-describedby="ask_price_time">
                                            <option value="">-Select-</option>
                                            @foreach ($ask_price_unit_option as $key => $eachOption)
                                            <option value="{{$key}}" {{ old("ask_price_unit") == $key ? "selected" : "" }}>{{$eachOption}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="field input-group">
                                        <label>&nbsp;</label>
                                        <select id="trademark_type" class="form-select" name="trademark_type" required="" value="{{ old('trademark_type') }}" aria-describedby="trademark_type">
                                            <option value="">-Select-</option>
                                            @foreach ($comman_type_option as $key => $eachOption)
                                            <option value="{{$key}}" {{ old("trademark_type") == $key ? "selected" : "" }}>{{$eachOption}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Agree to Terms and Conditions</legend>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label>
                                                    <input type="checkbox" required class="terms" id="term1">

                                                    I agree that the platform fee paid to Companydeals is non-refundable, even if no deal happens. The fee is only for access to listings and sharing contacts, not for ensuring any deal or response. There is no guarantee of buyer interest, seller action, or a successful transaction.


                                                </label><br>

                                                <label>
                                                    <input type="checkbox" required class="terms" id="term2"> I am solely responsible for checking the authenticity and legality of all parties and documents involved. Companydeals will not be held liable for any loss, fraud, or failure by any party in the deal.
                                                    Any issue found before or after the deal is entirely my responsibility.


                                                </label><br>

                                                <label>
                                                    <input type="checkbox" required class="terms" id="term3">I allow Companydeals to share my name, phone number, and email with the other party after payment. I accept that all communication and transactions are at my own risk. I will not hold Companydeals responsible for any dispute, miscommunication, or failed deal.
                                                    <!-- <b>All the entries of Companies, Properties, Trademarks and Assignments (in Seller & buyer Section both) must reflect on top as and when edited.</b> -->

                                                </label><br>
                                                <p class="text-danger d-none">You must agree to all terms before submitting.</p>
                                            </div>
                                        </div>

                                    </fieldset>
                                </div>
                                <div class="col-xl-12 mt-2">
                                    <button class="cta-primary" type="submit">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@push('plugin-styles')
<link rel="stylesheet" href="{{asset('adminassets/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}">
@endpush
@push('plugin-scripts')
<script src="{{asset('adminassets/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
<script type="text/javascript">
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        startDate: '-0m'
    });
</script>
@endpush
@endsection