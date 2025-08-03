@foreach ($assignmentArr as $assignment)
    <div class="col-md-6">
        <div class="cards">
            <div class="card-featured">
                <article>
                    <ul class="feature-list">
            
            <li>Category: {{$assignment['category']}}</li>
            <li>Subject: {{$assignment['subject']}}</li>
            <li>Brief of the work: {{$assignment['description']}}</li>
            <li><b>Ask Price: {{$assignment['deal_price']}} {{$assignment['deal_price_unit']}} {{$assignment['assignment_pricing_type']}}</b></li>         
            <li>Status : {{$assignment['is_active']}}</li>
            <li> 
                <a href="{{ route('user.buyer.assignment.addtointerested', $assignment['id']) }}" class="cta-primary interested_assignment" type="submit">I am interested in this assignment</a>
            </li>
        </ul>
    </article>
</div>
</div>
    </div>
@endforeach