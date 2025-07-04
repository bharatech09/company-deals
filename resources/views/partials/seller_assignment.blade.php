<div class="cards">
    <div class="card-featured">
        <article>
            <ul class="feature-list">
                @if($assignment['no_interested_buyer']>0)
                <li>No. of interested buyers: {{$assignment['no_interested_buyer']}}</li>

                @endif

                @if(count($assignment['buyers'])>0 && $assignment['deal_closed'] !=1)
                <li>Buyers</li>
                <ul style="border-bottom: 1px solid black; padding: 10px;">
                    @foreach ($assignment['buyers'] as $eachBuyer)
                    <ol>
                        {!!$eachBuyer['buyerDetail']!!}
                        <div class="close_deal_btn">
                            <a class="cta-primary mt-4" href="{{ route('user.seller.assignment.closedeal',['id'=>$assignment['id'],'buyer_id'=>$eachBuyer['buyer_id']]) }}">Close deal</a>
                        </div>
                    </ol>
                    @endforeach
                </ul>
                @endif

                <li><strong>Status:

                        @if($assignment['is_active'] == 'inactive' && $assignment['approved']==0)
                        Pending for approval
                        @elseif($assignment['is_active'] == 'inactive' && $assignment['approved']==1)
                        Inactive

                        @else
                        {{$assignment['is_active']}}
                        @endif()

                    </strong></li>
                <li>Category: {{$assignment['category']}}</li>
                <li>Subject: {{$assignment['subject']}}</li>
                <li>Brief of the work: {{$assignment['description']}}</li>
                <li>Minimum Deal Value: {{$assignment['deal_price']}} {{$assignment['deal_price_unit']}}</li>
                <li>Status: {{$assignment['is_active']}}</li>
                @if($assignment['deal_closed'] ==1 && $assignment['buyer_id'] > 0 )
                <li>Buyer Details: <br> {!!$assignment['finalBuyer']!!}</li>
                @endif
            </ul>
            @if($assignment['approved'] == 1)
            @if($assignment['payment_id'] ==null)

            <div class="col-md-6">
                <a class="cta-primary mt-4" href="#">Payment Pending </a>
            </div>
            @endif()
            @endif
            @if($assignment['deal_closed']!=1)
            <div class="col-md-6">
                @if($assignment['approved'] == 0)
                <a class="cta-primary mt-4" href="{{ route('user.seller.editassignment', $assignment['id'])}}" type="submit">Edit</a>

                @endif()
            </div>
            @endif
        </article>
    </div>
</div>