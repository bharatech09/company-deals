<div class="cards">
    <div class="card-featured">
        <article>
            <ul class="feature-list">
                @if($property['no_interested_buyer']>0)
                <li>No. of interested buyers: {{$property['no_interested_buyer']}}</li>

                @endif

                @if(count($property['buyers'])>0 && $property['deal_closed'] !=1)
                <li>Buyers</li>
                <ul style="border-bottom: 1px solid black; padding: 10px;">
                    @foreach ($property['buyers'] as $eachBuyer)
                    <ol>
                        {!!$eachBuyer['buyerDetail']!!}
                        <div class="close_deal_btn">
                            <a class="cta-primary mt-4" href="{{ route('user.seller.closedeal',['id'=>$property['id'],'buyer_id'=>$eachBuyer['buyer_id']]) }}">Close deal</a>
                        </div>
                    </ol>
                    @endforeach
                </ul>
                @endif
                <!-- <li>URN: {{$property['urn']}}</li> -->
                <li><strong>Status:



                        @if($property['status'] == 'inactive' && $property['approved']==0)
                        Pending for approval
                        @elseif($property['status'] == 'inactive' && $property['approved']==1)
                        Inactive


                        @else

                        {{$property['status']}}
                        @endif()




                    </strong>


                </li>
                <li>State: {{$property['state']}}</li>
                <li>Pincode: {{$property['pincode']}}</li>
                <li>Address: {{$property['address']}}</li>
                <li>Space: {{$property['space']}} Sq. ft.</li>
                <li>Type: {{$property['type']}}</li>
                <li>Ask price: {{$property['ask_price']}} {{$property['ask_price_unit']}} per month</li>

                @if($property['deal_closed'] ==1 && $property['buyer_id'] > 0 )
                <li  style="border-bottom:1px solid black;">Buyer Details: <br> {!!$property['finalBuyer']!!}</li>
                @endif

            </ul>
            <div class="row">
                @if($property['approved'] == 1)
                @if($property['payment_id'] ==null)
                <div class="col-md-6">
                    <a class="cta-primary mt-4" href="#">Payment Pending </a>
                </div>
                @endif()
                @endif
                @if($property['deal_closed']!=1)
                <div class="col-md-6">
                    @if($property['approved'] == 0)
                    <a class="cta-primary mt-4" href="{{ route('user.seller.editproperty',['id' => $property['id']]) }}" type="submit">Edit</a>
                    @endif()
                </div>
                @endif
            </div>

        </article>

    </div>
</div>