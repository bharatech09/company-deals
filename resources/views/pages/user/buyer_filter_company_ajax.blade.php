@foreach ($companyArr as $company)
    <div class="col-md-6">
        <div class="cards">
            <div class="card-featured">
                <article>
                    <ul class="feature-list">
             <li>Name: {{$company['name']}} {{$company['name_prefix']}}</li>
            <li>Type Of Entity: {{$company['type_of_entity']}}</li>
            <li>ROC: {{$company['roc']}}</li>
            <li>Year of Incorporation: {{$company['year_of_incorporation']}}</li>
            <li>Industry: {{$company['industry']}}</li>
            <li>Ask price: {{$company['ask_price']}} {{$company['ask_price_unit']}} per month</li>
            <li> 
                <a href="{{ route('user.buyer.company.addtointerested', $company['id']) }}" class="cta-primary interested_company" type="submit">I am interested in this Company</a>
            </li>
        </ul>
    </article>
</div>
</div>
    </div>
@endforeach
<input id="data_price_min" name="data_price_min" type="hidden" value="{{$filerData['priceMin']}}">
<input id="data_price_max" name="data_price_max" type="hidden" value="{{$filerData['priceMax']}}">
