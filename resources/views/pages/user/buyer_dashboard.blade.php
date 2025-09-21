@extends('layout.master')
@section('content')


    <!-- Bootstrap JS -->

    <section class="dashboard-wrap">
        <div class="container">
            <div class="row">
                @include('layout.buyer_nav')
                <div class="col-lg-8 col-xl-9">
                    <div class="dashboard-details">
                        <button class="navToggle2 cta-primary mb-4"><i class="fa-solid fa-sliders"></i> Open Dashboard
                            Nav</button>

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

                        <!-- Companies Section - Show Only Count -->
                        <div class="buyer-seller-details">
                            <header>
                                <h2>Companies Of Your Interest ({{ count($interestedCompanyArr) }})</h2>
                            </header>
                            <div style="border: 1px solid green;"></div>

                            <div class="row">
                                @foreach ($interestedCompanyArr as $company)
                                    <div class="col-md-6">
                                        <div class="cards">
                                            <div class="card-featured">
                                                <article>
                                                    <ul class="feature-list">
                                                        @if(isset($company['has_paid']) && $company['has_paid'] && $company['buyer_status'] == 'active')
                                                            @php
                                                                $updatedSeller = str_replace("No of deal closed", "</br><b>No of deal closed previously</b>", $company['seller']);
                                                                $updatedSeller = str_replace("amount of deal closed:", "</br><b>Amount of deal closed previously:</b>", $updatedSeller . '</br></br>');
                                                            @endphp
                                                            <li style="color:black;border-bottom: 2px solid  black;">
                                                                Seller Details:<br>
                                                                {!! $updatedSeller !!}
                                                            </li>
                                                        @elseif(!isset($company['has_paid']) || !$company['has_paid'])
                                                            <li>
                                                                <a href="{{ route('user.buyer.pay', ['type' => 'company', 'id' => $company['id']]) }}"
                                                                    class="cta-primary interested_assignment" type="submit">Pay to
                                                                    view Seller Details</a>
                                                            </li>
                                                        @endif

                                                        <li>Name: {{ $company['name'] }} {{ $company['name_prefix'] }}</li>
                                                        <li>Type Of Entity: {{ $company['type_of_entity'] }}</li>
                                                        <li>Ask Price: ₹{{ number_format($company['ask_price_amount']) }}
                                                            {{ $company['ask_price_unit'] }} per month
                                                        </li>
                                                        <li>Status: {{ ucfirst($company['status']) }}</li>

                                                        @if(!isset($company['has_paid']) || !$company['has_paid'])
                                                            <li>
                                                                <a href="{{ route('user.buyer.company.removefrominterested', $company['id']) }}"
                                                                    class="cta-primary interested_assignment" type="submit">I am
                                                                    not interested in this company</a>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </article>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="buyer-seller-details">
                            <header>
                                <h2>Properties of your interest ({{ count($interestedPropertyArr) }})</h2>
                            </header>
                            <div style="border: 1px solid green;"></div>

                            <div class="row">

                                @foreach ($interestedPropertyArr as $property)
                                    <div class="col-md-6">
                                        <div class="cards">
                                            <div class="card-featured">
                                                <article>
                                                    <ul class="feature-list">
                                                        @if(isset($property['has_paid']) && $property['has_paid'] && $property['buyer_status'] == 'active')
                                                            <li
                                                                style="
                                                                                                                                        border-bottom: 1px solid black;
                                                                                                                                    ">
                                                                Sellar
                                                                Details:
                                                                <br />
                                                                {!!$property['seller']!!}

                                                                <hr>

                                                            </li>

                                                        @elseif(!isset($property['has_paid']) || !$property['has_paid'])
                                                            <li>
                                                                <a href="{{ route('user.buyer.pay', ['type' => 'property', 'id' => $property['id']]) }}"
                                                                    class="cta-primary interested_assignment" type="submit">Pay to
                                                                    view Seller Details</a>
                                                            </li>
                                                        @endif
                                                        <li>State: {{$property['state']}}</li>
                                                        <li>Pincode: {{$property['pincode']}}</li>
                                                        <li>Address: {{$property['address']}}</li>
                                                        <li>Space: {{$property['space']}} Sq. Ft.</li>
                                                        <li>Type: {{$property['type']}}</li>
                                                        <li>Ask price: {{$property['ask_price']}}
                                                            {{$property['ask_price_unit']}} {{$property['property_type']}}
                                                        </li>
                                                        <li>Status: {{$property['status']}}</li>

                                                        @if($property['buyer_status'] != 'active')
                                                            <li>
                                                                <a href="{{ route('user.buyer.property.removefrominterested', $property['id']) }}"
                                                                    class="cta-primary interested_assignment" type="submit">I am not
                                                                    interested in this property</a>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </article>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="buyer-seller-details">
                            <header>
                                <h2>NOC trademarks of your interest ({{ count($interestedTrademarkArr) }})</h2>
                            </header>
                            <div style="border: 1px solid green;"></div>

                            <div class="row">
                                @foreach ($interestedTrademarkArr as $trademark)
                                    <div class="col-md-6">
                                        <div class="cards">
                                            <div class="card-featured">
                                                <article>
                                                    <ul class="feature-list">
                                                        @if(isset($trademark['has_paid']) && $trademark['has_paid'] && $trademark['buyer_status'] == 'active')
                                                            <li style="border-bottom: 1px solid black;">Sellar Details: <br />
                                                                {!!$trademark['seller']!!}</li>
                                                        @elseif(!isset($trademark['has_paid']) || !$trademark['has_paid'])
                                                            <li>
                                                                <a href="{{ route('user.buyer.pay', ['type' => 'trademark', 'id' => $trademark['id']]) }}"
                                                                    class="cta-primary interested_assignment" type="submit">Pay to
                                                                    view Seller Details</a>
                                                            </li>
                                                        @endif
                                                        <li>Word Mark: {{$trademark['wordmark']}}</li>
                                                        <li> Application Number: {{$trademark['application_no']}}</li>
                                                        <li>Class: {{$trademark['class_no']}}</li>
                                                        <li>Proprietor: {{$trademark['proprietor']}}</li>
                                                        <li>Status: {{$trademark['status']}}</li>
                                                        <li>Valid Upto: {{date('j F, Y', strtotime($trademark['valid_upto']))}}
                                                        </li>
                                                        <li> Description: {{$trademark['description']}}</li>
                                                        <li>Ask price: {{$trademark['ask_price']}}
                                                        
                                                            {{$trademark['ask_price_unit']}}  @if($trademark['trademark_type'] == 0)
                    per month
                  @else
  per year
                @endif
                                                        </li>
                                                        <li>Status: {{$trademark['is_active']}}</li>
                                                        @if($trademark['buyer_status'] == 'inactive')
                                                            <li>
                                                                <a href="{{ route('user.buyer.trademark.removefrominterested', $trademark['id']) }}"
                                                                    class="cta-primary interested_assignment" type="submit">I am not
                                                                    interested </a>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </article>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>


                        <!-- Assignments Section - Show Only Count -->
                        <div class="buyer-seller-details">
                            <header>
                                <h2>Assignment of your interest ({{ count($interestedAssignmentArr) }})</h2>
                            </header>
                            <div style="border: 1px solid green;"></div>

                            <div class="row">
                                @foreach ($interestedAssignmentArr as $assignment)
                                    <div class="col-md-6">
                                        <div class="cards">
                                            <div class="card-featured">
                                                <article>
                                                    <ul class="feature-list">
                                                        @if(isset($assignment['has_paid']) && $assignment['has_paid'] && $assignment['buyer_status'] == 'active')
                                                            <li style="border-bottom:1px solid black ;">Seller Details: <br />
                                                                {!!$assignment['seller']!!}</li>
                                                        @elseif(!isset($assignment['has_paid']) || !$assignment['has_paid'])
                                                            <li>
                                                                <a href="{{ route('user.buyer.pay', ['type' => 'assignment', 'id' => $assignment['id']]) }}"
                                                                    class="cta-primary interested_assignment" type="submit">Pay to
                                                                    view Seller Details</a>
                                                            </li>
                                                        @endif
                                                        <li>Category: {{$assignment['category']}}</li>
                                                        <li>Subject: {{$assignment['subject']}}</li>
                                                        <li>Minimum Deal Value: {{$assignment['deal_price']}}
                                                            {{$assignment['deal_price_unit']}} {{$assignment['assignment_pricing_type']}}
                                                        </li>
                                                        <li>Status: {{$assignment['is_active']}}</li>
                                                        @if($assignment['buyer_status'] == 'inactive')
                                                            <li>
                                                                <a href="{{ route('user.buyer.assignment.removefrominterested', $assignment['id']) }}"
                                                                    class="cta-primary interested_assignment" type="submit">I am not
                                                                    interested in this assignment</a>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </article>
                                            </div>
                                        </div>
                                    </div>

                                @endforeach
                            </div>
                        </div>


                        <div class="buyer-seller-details">
                            <header class="text-center">
                                <h2>Deal Closed</h2>
                            </header>
                            <div style="border: 1px solid green;"></div>

                            @if(count($dealClosedCompanyCompanyArr) > 0)

                                <header class=" mt-2">
                                    <h2>Companies Deal Closed</h2>
                                </header>
                                <div class="row">
                                    @foreach ($dealClosedCompanyCompanyArr as $company)
                                        <div class="col-md-6">
                                            <div class="cards">
                                                <div class="card-featured text-center">
                                                    <article>
                                                        <ul class="feature-list">

                                                            @if(isset($company['has_paid']) && $company['has_paid'] && $company['buyer_status'] == 'active')
                                                                @php
                                                                    $updatedSeller = str_replace("No of deal closed", "</br><b>No of deal closed previously</b>", $company['seller']);
                                                                    $updatedSeller = str_replace("amount of deal closed:", "</br><b>Amount of deal closed previously:</b>", $updatedSeller . '</br></br>');
                                                                @endphp
                                                                <li style="color:black;border-bottom: 2px solid  black;">
                                                                    Seller Details:<br>
                                                                    {!! $updatedSeller !!}

                                                                </li>
                                                            @elseif(!isset($company['has_paid']) || !$company['has_paid'])
                                                                <li>
                                                                    <a href="{{ route('user.buyer.pay') }}"
                                                                        class="cta-primary interested_assignment" type="submit">Pay to
                                                                        view Seller Details</a>
                                                                </li>
                                                            @endif

                                                            <li>Name: {{ $company['name'] }} {{ $company['name_prefix'] }}</li>
                                                            <li>Type Of Entity: {{ $company['type_of_entity'] }}</li>
                                                            <li>ROC: {{ $company['roc'] }}</li>
                                                            <li>Year of Incorporation: {{ $company['year_of_incorporation'] }}</li>
                                                            <li>Industry: {{ $company['industry'] }}</li>
                                                            <li>Ask Price: ₹{{ number_format($company['ask_price_amount']) }}
                                                                {{ $company['ask_price_unit'] }} per month
                                                            </li>
                                                            <li>Status: {{ ucfirst($company['status']) }}</li>

                                                            @if($company['buyer_status'] == 'inactive')
                                                                <li>
                                                                    <a href="{{ route('user.buyer.company.removefrominterested', $company['id']) }}"
                                                                        class="cta-primary interested_assignment" type="submit">I am not
                                                                        interested in this company</a>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </article>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                            @endif()
                            @if(count($dealClosedPropertyArr) > 0)


                                <header class=" mt-5">
                                    <h2>Property Deal Closed</h2>
                                </header>
                                <div class="row">


                                    @foreach ($dealClosedPropertyArr as $property)
                                        <div class="col-md-6">
                                            <div class="cards">
                                                <div class="card-featured">
                                                    <article>
                                                        <ul class="feature-list">
                                                            @if(isset($property['has_paid']) && $property['has_paid'] && $property['buyer_status'] == 'active')
                                                                <li>Seller Details: <br />
                                                                    {!!$property['seller']!!}</li>
                                                            @elseif(!isset($property['has_paid']) || !$property['has_paid'])
                                                                <li>
                                                                    <a href="{{ route('user.buyer.pay', ['type' => 'property', 'id' => $property['id']]) }}"
                                                                        class="cta-primary interested_assignment" type="submit">Pay to
                                                                        view Seller Details</a>
                                                                </li>
                                                            @endif
                                                            <li>State: {{$property['state']}}</li>
                                                            <li>Pincode: {{$property['pincode']}}</li>
                                                            <li>Space: {{$property['space']}} Sq. Ft.</li>
                                                            <li>Type: {{$property['type']}}</li>
                                                            <li>Ask price: {{$property['ask_price']}}
                                                                {{$property['ask_price_unit']}} per month
                                                            </li>
                                                            <li>Status: {{$property['status']}}</li>

                                                            @if($property['buyer_status'] != 'active')
                                                                <li>
                                                                    <a href="{{ route('user.buyer.property.removefrominterested', $property['id']) }}"
                                                                        class="cta-primary interested_assignment" type="submit">I am not
                                                                        interested in this property</a>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </article>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif()




                            @if(count($dealClosedTrademarkArr) > 0)


                                <header class=" mt-5">
                                    <h2>Trademark Deal Closed</h2>
                                </header>
                                <div class="row">


                                    @foreach ($dealClosedTrademarkArr as $trademark)
                                        <div class="col-md-6">
                                            <div class="cards">
                                                <div class="card-featured">
                                                    <article>
                                                        <ul class="feature-list">
                                                            @if(isset($trademark['has_paid']) && $trademark['has_paid'] && $trademark['buyer_status'] == 'active')
                                                                <li style="border-bottom:1px solid black ;">Selleassigbr Details: <br />
                                                                    {!!$trademark['seller']!!}</li>
                                                            @elseif(!isset($trademark['has_paid']) || !$trademark['has_paid'])
                                                                <li>
                                                                    <a href="{{ route('user.buyer.pay', ['type' => 'trademark', 'id' => $trademark['id']]) }}"
                                                                        class="cta-primary interested_assignment" type="submit">Pay to
                                                                        view Seller Details</a>
                                                                </li>
                                                            @endif
                                                            <li>Word Mark: {{$trademark['wordmark']}}</li>
                                                            <li> Application Number: {{$trademark['application_no']}}</li>
                                                            <li>Class: {{$trademark['class_no']}}</li>
                                                            <li>Proprietor: {{$trademark['proprietor']}}</li>
                                                            <li>Status: {{$trademark['status']}}</li>
                                                            <li>Valid Upto: {{date('j F, Y', strtotime($trademark['valid_upto']))}}
                                                            </li>
                                                            <li> Description: {{$trademark['description']}}</li>
                                                            <li>Ask price: {{$trademark['ask_price']}}
                                                                {{$trademark['ask_price_unit']}} per month
                                                            </li>
                                                            <li>Status: {{$trademark['is_active']}}</li>
                                                            @if($trademark['buyer_status'] == 'inactive')
                                                                <li>
                                                                    <a href="{{ route('user.buyer.trademark.removefrominterested', $trademark['id']) }}"
                                                                        class="cta-primary interested_assignment" type="submit">I am not
                                                                        interested in this NOC of trademark</a>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </article>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif()



                            @if(count($dealClosedAssignmentArr) > 0)


                                <header class=" mt-5">
                                    <h2>Assigments Deal Closed</h2>
                                </header>
                                <div class="row">


                                    @foreach ($dealClosedAssignmentArr as $assignment)
                                        <div class="col-md-6">
                                            <div class="cards">
                                                <div class="card-featured">
                                                    <article>
                                                        <ul class="feature-list">
                                                            @if(isset($assignment['has_paid']) && $assignment['has_paid'] && $assignment['buyer_status'] == 'active')
                                                                <li style="border-bottom:1px solid black ;">Seller Details: <br />
                                                                    {!!$assignment['seller']!!}</li>
                                                            @elseif(!isset($assignment['has_paid']) || !$assignment['has_paid'])
                                                                <li>
                                                                    <a href="{{ route('user.buyer.pay', ['type' => 'assignment', 'id' => $assignment['id']]) }}"
                                                                        class="cta-primary interested_assignment" type="submit">Pay to
                                                                        view Seller Details</a>
                                                                </li>
                                                            @endif
                                                            <li>Category: {{$assignment['category']}}</li>
                                                            <li>Subject: {{$assignment['subject']}}</li>
                                                            <li>Brief of the work: {{$assignment['description']}}</li>
                                                            <li>Minimum Deal Value: {{$assignment['deal_price']}}
                                                                {{$assignment['deal_price_unit']}}
                                                            </li>
                                                            <li>Status: {{$assignment['is_active']}}</li>
                                                            @if($assignment['buyer_status'] == 'inactive')
                                                                <li>
                                                                    <a href="{{ route('user.buyer.assignment.removefrominterested', $assignment['id']) }}"
                                                                        class="cta-primary interested_assignment" type="submit">I am not
                                                                        interested in this assignment</a>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </article>
                                                </div>
                                            </div>
                                        </div>

                                    @endforeach
                                </div>
                            @endif()
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


@endsection