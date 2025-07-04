<div class="cards">
    <div class="card-featured">
        <article>
            <ul class="feature-list">
                @if($company['no_interested_buyer']>0)
                <li>No. of interested buyers: {{$company['no_interested_buyer']}}</li>

                @endif

                @if(count($company['buyers'])>0 && $company['deal_closed'] !=1)
                <li>Buyers</li>
                <ul>
                    @foreach ($company['buyers'] as $eachBuyer)
                    <ol>
                        {!!$eachBuyer['buyerDetail']!!}
                        <div class="close_deal_btn">
                            <a class="cta-primary mt-4" href="{{ route('user.seller.closedealcompany',['id'=>$company['id'],'buyer_id'=>$eachBuyer['buyer_id']]) }}">Close deal</a>
                        </div>
                    </ol>
                    @endforeach
                </ul>
                @endif
                <li><strong>Status: {{$company['status']}}</strong></li>
                <li>Type Of Entity: {{$company['type_of_entity']}}</li>
                <li>ROC: {{$company['roc']}}</li>
                <li>Name of Company/LLP: {{$company['name']}} {{$company['name_prefix']}}</li>
                <!-- <li>CIN/LLPIN: {{$company['cin_llpin']}}</li> -->
                <li>Year of Incorporation: {{$company['year_of_incorporation']}}</li>
                <li>Industry: {{$company['industry']}}</li>
                @if($company['deal_closed'] ==1)
                <li>Buyer Details: <br> {!!$company['finalBuyer']!!}</li>
                @endif


            </ul>
            <div class="row">
                @if($company['status'] == 'inactive')
                <div class="col-md-6">
                    <a class="cta-primary mt-4" href="{{ route('user.seller.company.payment', ['company_id' => $company['id']]) }}">Payment Pending</a>
                </div>
                @endif
                @if($company['deal_closed']!=1)
                <div class="col-md-6">
                    <a class="cta-primary mt-4" href="{{ route('user.seller.companyform.showstep1',['id' => $company['id']]) }}" type="submit">Edit</a>
                </div>
                @endif
            </div>
        </article>
    </div>
</div>