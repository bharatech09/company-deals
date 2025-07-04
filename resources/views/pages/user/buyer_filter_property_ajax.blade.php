
@foreach ($propertyArr as $property)
    <div class="col-md-6">
        <div class="cards">
            <div class="card-featured">
                <article>
                    <ul class="feature-list">
                    <li>State: {{$property['state']}}</li>
                    <li>Pincode: {{$property['pincode']}}</li>
                    <li>Space: {{$property['space']}} Sq ft.</li>
                    <li>Type: {{$property['type']}}</li>
                    <li>Ask price: {{$property['ask_price']}} {{$property['ask_price_unit']}} per month</li>
                    <li> 
                        <a href="{{ route('user.buyer.property.addtointerested', $property['id']) }}" class="cta-primary interested_property" type="submit">I am interested in this Property</a>
                    </li>
        </ul>
    </article>
</div>
</div>
    </div>
@endforeach
<input id="data_price_min" name="data_price_min" type="hidden" value="{{$filerData['priceMin']}}">
<input id="data_price_max" name="data_price_max" type="hidden" value="{{$filerData['priceMax']}}">
<input id="data_space_min" name="data_space_min" type="hidden" value="{{$filerData['spaceMin']}}">
<input id="data_space_max" name="data_space_max" type="hidden" value="{{$filerData['spaceMax']}}">
