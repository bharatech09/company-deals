@php
    if (is_null($trademark['valid_upto'])) {
        $valid_upto = "";
    } else {
        $valid_upto = date('j F, Y', strtotime($trademark['valid_upto']));
    }

@endphp
<style>
    .cards .card-featured article p {
        -webkit-box-orient: horizontal !important;
    }
</style>

<div class="cards">
    <div class="card-featured">
        <article>
            <ul class="feature-list">
                @if($trademark['no_interested_buyer'] > 0)
                    <li>No. of interested buyers: {{$trademark['no_interested_buyer']}}</li>

                @endif

                @if(count($trademark['buyers']) > 0 && $trademark['deal_closed'] != 1)
                    <li>Buyers</li>
                    <ul style="border-bottom: 1px solid black; padding: 10px;">
                        @foreach ($trademark['buyers'] as $eachBuyer)
                            <ol>
                                {!!$eachBuyer['buyerDetail']!!}
                                <div class="close_deal_btn">
                                    <a class="cta-primary mt-4"
                                        href="{{ route('user.seller.closedealnoc', ['id' => $trademark['id'], 'buyer_id' => $eachBuyer['buyer_id']]) }}">Close
                                        deal</a>
                                </div>
                            </ol>
                        @endforeach
                    </ul>
                @endif
                @if($trademark['is_active'] == 'inactive' && $trademark['approved'] == 0)


                    <div class="alert alert-warning" role="alert">

                        <p class="mb-1" style="font-size:14px;">
                            Your entry will be reviewed by the Admin within <strong>12 hours</strong>.<br>
                            Once approved, the status will change to <br> <strong>'Inactive'</strong>, and you may proceed
                            with
                            the
                            payment.
                        </p>
                        <p class="mb-0 text-danger" style="font-size:14px;">
                            <strong>Note:</strong> Please do <u>not</u> include your email address or contact number in the
                            entry field.<br>
                            Such entries will <strong>not be approved</strong>.
                        </p>
                    </div>
                @endif()
                <!--   <li>URN: {{$trademark['urn']}} </li> -->
                <li><strong>Status:


                        @if($trademark['is_active'] == 'inactive' && $trademark['approved'] == 0)
                            Pending for approval
                        @elseif($trademark['is_active'] == 'inactive' && $trademark['approved'] == 1)
                            Inactive


                        @else

                            {{$trademark['is_active']}}
                        @endif()



                        <!-- {{$trademark['is_active']}} -->





                    </strong></li>
                <li>Word Mark: {{$trademark['wordmark']}}</li>
                <li> Application Number: {{$trademark['application_no']}}</li>
                <li>Class: {{$trademark['class_no']}}</li>
                <li>Proprietor: {{$trademark['proprietor']}}</li>
                <li>Status: {{$trademark['status']}}</li>
                <li>Valid Upto: {{$valid_upto}}</li>
                <li> Description: {{$trademark['description']}}</li>
                <li><b>Ask Price: {{$trademark['ask_price']}} {{$trademark['ask_price_unit']}} per month</b></li>
                @if($trademark['deal_closed'] == 1 && $trademark['buyer_id'] > 0)
                    <li>Buyer Details: <br> {!!$trademark['finalBuyer']!!}</li>
                @endif

            </ul>
            @if($trademark['approved'] == 1)
                @if($trademark['payment_id'] == null)
                    <div class="col-md-6">
                        <a class="cta-primary mt-4" href="{{ route('user.seller.trademark.payment', ['trademark_id' => $trademark['id']]) }}">Pay ₹100 to Activate</a>
                    </div>
                @endif
            @endif
            @if($trademark['deal_closed'] != 1)

                @if($trademark['approved'] == 0)

                    <div class="col-md-6">
                        <a class="cta-primary mt-4" href="{{ route('user.seller.editnoctrademark', $trademark['id'])}}"
                            type="submit">Edit</a>
                    </div>

                @endif()
            @endif
        </article>
    </div>
</div>