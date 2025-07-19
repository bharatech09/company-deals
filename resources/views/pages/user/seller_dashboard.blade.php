@extends('layout.master')
@section('content')
    <section class="dashboard-wrap">
        <div class="container">
            <div class="row">
                @include('layout.seller_nav')
                <div class="col-lg-8 col-xl-9">
                    <div class="dashboard-details">
                        <button class="navToggle2 cta-primary mb-4"><i class="fa-solid fa-sliders"></i> Open Dashboard
                            Nav</button>

                        <!-- Seller Payment Button Start -->

                        <!-- Seller Payment Button End -->

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

                        <div class="buyer-seller-details">
                            <header>
                                <h2>Companies Open for Buyer Interest.</h2>
                            </header>
                            <div style="border: 1px solid green;"></div>


                            <div class="row">

                                @foreach ($activeCompanyArr as $company)
                                    <div class="col-md-6">
                                        @include('partials.seller_company', ['company' => $company])
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="buyer-seller-details">
                            <header>
                                <h2>Properties Open for Buyer Interest</h2>
                            </header>
                            <div style="border: 1px solid green;"></div>


                            <div class="row">

                                @foreach ($activePropertyArr as $property)
                                    <div class="col-md-6">
                                        @include('partials.seller_property', ['property' => $property])
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="buyer-seller-details">
                            <header>
                                <h2>Trademarks Open for Buyer Interest</h2>
                            </header>
                            <div style="border: 1px solid green;"></div>

                            <div class="row">
                                @foreach ($activeTrademarkArr as $trademark)
                                    <div class="col-md-6">
                                        @include('partials.seller_trademark', ['trademark' => $trademark])
                                    </div>
                                @endforeach
                            </div>
                        </div>


                        <div class="buyer-seller-details">
                            <header>
                                <h2>Assignments Open for Buyer Interest.</h2>
                            </header>
                            <div style="border: 1px solid green;"></div>

                            <div class="row">
                                @foreach ($activeAssignmentArr as $assignment)
                                    <div class="col-md-6">
                                        @include('partials.seller_assignment', ['assignment' => $assignment])
                                    </div>
                                @endforeach



                            </div>
                        </div>


                        <div>
                            <h2 class="text-center" style="border-bottom: 2px solid black;">Deal Closed</h2>
                            <hr>

                            @if(count($dealClosedCompanyArr) > 0)

                                <div class="buyer-seller-details">
                                    <header>
                                        <h2>Companies Deal Closed</h2>
                                    </header>

                                    <div class="row">

                                        @foreach ($dealClosedCompanyArr as $company)
                                            <div class="col-md-6">
                                                @include('partials.seller_company', ['company' => $company])
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif()

                            @if(count($dealClosedPropertyArr) > 0)

                                <div class="buyer-seller-details">
                                    <header>
                                        <h2>Properties Deal Closed</h2>
                                    </header>

                                    <div style="border: 1px solid green;"></div>

                                    <div class="row">

                                        @foreach ($dealClosedPropertyArr as $property)
                                            <div class="col-md-6">
                                                @include('partials.seller_property', ['property' => $property])
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                            @endif()

                            @if(count($dealClosedTrademarkArr) > 0)
                                <div class="buyer-seller-details">
                                    <header>
                                        <h2>Trademarks Deal Closed</h2>
                                    </header>
                                    <div style="border: 1px solid green;"></div>

                                    <div class="row">
                                        @foreach ($dealClosedTrademarkArr as $trademark)
                                            <div class="col-md-6">
                                                @include('partials.seller_trademark', ['trademark' => $trademark])
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                            @endif()

                            @if(count($dealClosedAssignmentArr) > 0)
                                <div class="buyer-seller-details">
                                    <header>
                                        <h2>Assignments Deal Closed</h2>
                                    </header>
                                    <div style="border: 1px solid green;"></div>

                                    <div class="row">
                                        @foreach ($dealClosedAssignmentArr as $assignment)
                                            <div class="col-md-6">
                                                @include('partials.seller_assignment', ['assignment' => $assignment])
                                            </div>
                                        @endforeach



                                    </div>
                                </div>

                            @endif()


                        </div>


                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection