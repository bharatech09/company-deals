@foreach ($trademarkArr as $trademark)
    <div class="col-md-6">
        <div class="cards">
            <div class="card-featured">
                <article>
                    <ul class="feature-list">
            
            <li>Word Mark: {{$trademark['wordmark']}}</li>
            <li> Application Number: {{$trademark['application_no']}}</li>
            <li>Class: {{$trademark['class_no']}}</li>
            <li>Proprietor: {{$trademark['proprietor']}}</li>
            <li>Status: {{$trademark['status']}}</li>
            <li>Valid Upto: {{date('j F, Y', strtotime($trademark['valid_upto']))}}</li>
            <li> Description: {{$trademark['description']}}</li>
            <li>Ask Description: {{$trademark['ask_price']}} {{$trademark['ask_price_unit']}} per month</li>
            <li> 
                <a href="{{ route('user.buyer.trademark.addtointerested', $trademark['id']) }}" class="cta-primary interested_trademark" type="submit">I am interested in this NOC of trademark</a>
            </li>
        </ul>
    </article>
</div>
</div>
    </div>
@endforeach
<input id="data_price_min" name="data_price_min" type="hidden" value="{{$filerData['priceMin']}}">
<input id="data_price_max" name="data_price_max" type="hidden" value="{{$filerData['priceMax']}}">
