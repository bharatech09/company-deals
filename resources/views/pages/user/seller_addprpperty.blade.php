@extends('layout.master')
@section('content')
<section class="dashboard-wrap">
    <div class="container">
        <div class="row">
            @include('layout.seller_nav')
            <div class="col-lg-8 col-xl-9">
                <div class="dashboard-details">
                    <button class="navToggle2 cta-primary mb-4"><i class="fa-solid fa-sliders"></i> Open Dashboard Nav</button>
                    <div class="form-wrap">
                        <header>
                            <h2>Submit your property detail</h2>
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
                        @php
                        $states_option = Config::get('selectoptions.states');
                        $ask_price_unit_option = Config::get('selectoptions.ask_price_unit_option');
                        $property_type_option = Config::get('selectoptions.property_type_option');
                        @endphp
                        <form action="{{ route('user.seller.saveproperty') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="field">
                                        <label for="state">State</label>
                                        <select id="state" class="form-select" name="state" required="">
                                            <option value="">-Select-</option>
                                            @foreach ($states_option as $key => $stateName)
                                            <option value="{{$key}}" {{ old("state") == $key ? "selected" : "" }}>{{$stateName}}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field">
                                        <label for="pincode">Pin code</label>
                                        <input id="pincode" maxlength="6" type="number" class="form-control" name="pincode" placeholder="Pin code" required="" value="{{ old('pincode') }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="field">
                                        <label for="address">Address</label>
                                                                       <b>Please do not put your email or mobile number in this field</b>

                                        <input id="address" type="text" class="form-control" name="address" placeholder="Address" required="" value="{{ old('address') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group field">

                                        <label for="space">Space</label>
                                        <input id="space" maxlength="4" type="text" class="form-control" name="space" placeholder="Space" required="" value="{{ old('space') }}" aria-describedby="space_unit"><span class="input-group-text" class="space_unit">Sq ft.</span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="field">
                                        <label for="space">Type</label>
                                        <select id="type" class="form-select" name="type" required="">
                                            <option value="">-Select-</option>
                                            @foreach ($property_type_option as $key => $eachOption)
                                            <option value="{{$key}}" {{ old("type") == $key ? "selected" : "" }}>{{$eachOption}}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field">
                                        <label for="ask_price">Ask price</label>
                                        <input id="ask_price" type="number" class="form-control" name="ask_price" placeholder="Ask price" required="" value="{{ old('ask_price') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group field">

                                        <label>&nbsp;</label>
                                        <select id="ask_price_unit" class="form-select" name="ask_price_unit" required="" aria-describedby="ask_price_time">
                                            <option value="">-Select-</option>
                                            @foreach ($ask_price_unit_option as $key => $eachOption)
                                            <option value="{{$key}}" {{ old("ask_price_unit") == $key ? "selected" : "" }}>{{$eachOption}}</option>
                                            @endforeach
                                        </select>
                                        <span class="input-group-text" id="ask_price_time">per month</span>


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

@endsection