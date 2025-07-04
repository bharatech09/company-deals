@extends('admin.layout.master')
@section('content')
<div class="row">
  <div class="col-12 grid-margin">
    <div class="card">
      <div class="card-body">
      	<a type="button" href="{{ url()->previous() }}"  class="btn btn-primary me-2">Back</a>
        <h4 class="card-title">Property Detail</h4>
        @if(session('message'))
        <div class="alert alert-success">
              <div> {{ session('message') }}</div>
        </div>
        @endif
        @if($errors->any())
        	<div class="alert alert-danger">
		    @foreach ($errors->all() as $error)
		        <div>{{ $error }}</div>
		    @endforeach
			</div>
		@endif
		<div class="row">
			<div class="col-md-12 text-right">
				@if($property->home_featured)
					<a type="button" href="#"  class="btn btn-primary me-2">Featured</a>
				@else
				<a type="button" href="{{ route('admin.property.togglefeatured', $property->id) }}"  class="btn btn-primary me-2">Set Featured</a>
				@endif
			</div>
		</div>
		<div class="row gy-4">
	        <div class="col-xl-4 col-lg-4 col-4 grid-margin">
	    		<div class="card">

	      			<div class="card-body">

	        			<h5 class="card-title">Property Detail</h5>
	        			<ul class="list-group">
				            <li class="list-group-item">URN: {{$property->urn}}</li>
				            <li class="list-group-item">State: {{$property->state}}</li>
				            <li class="list-group-item">Pincode: {{$property->pincode}}</li>
				            <li class="list-group-item">Address: {{$property->address}}</li>
				            <li class="list-group-item">Space: {{$property->space}}</li>
				            <li class="list-group-item">Type: {{$property->type}}</li>
				            <li class="list-group-item">Ask price: {{$property->ask_price}} {{$property->ask_price_unit}} per month</li>
				            </ul>
	        		</div>
	        	</div>
	        </div>
	        <div class="col-xl-4 col-lg-4 col-4 grid-margin">
	    		<div class="card">
	      			<div class="card-body">
	        			<h5 class="card-title">User Detail</h5>
	        			<ul class="list-group">
				            <li class="list-group-item">
				            	Name: {{$user->name}}
				            </li>
				            <li class="list-group-item">
				            	Email: {{$user->email}}
				            </li>
				            <li class="list-group-item">
				            	Email verified? @if($user->email_verified == 1)
			                    <span class="mdi mdi-check"></span>
			                   @else
			                   <span class="mdi mdi-close"></span>
			                   
			                  @endif
				            </li>
				            <li class="list-group-item">
				            	Phone {{$user->phone}}
				            </li>
				            <li class="list-group-item">
				            	Phone verified?
				            	@if($user->phone_verified == 1)
			                    <span class="mdi mdi-check"></span>
			                   @else
			                   <span class="mdi mdi-close"></span>
			                   
			                  @endif
				            </li>
				          </ul>
	        		</div>
	        	</div>
	        </div>
	        <div class="col-xl-4 col-lg-4 col-4 grid-margin">
	    		<div class="card">
	      			<div class="card-body">
	        			<h5 class="card-title"> Payment Detail</h5>
	        			@foreach ($payments as $payment)
	        			<ul class="list-group">
				            <li class="list-group-item">
				            	Amount: {{$payment['amount']}}
				            </li>
				            <li class="list-group-item">
				            	Status: {{$payment['status']}}
				            </li>
				            <li class="list-group-item">
				            	Payment from: {{$payment['payment_from']}}
				            </li>
				            <li class="list-group-item">
				            	Payment by: {{$payment['user']}}
				            </li>
				            <li class="list-group-item">
				            	Service start date: {{$payment['service_start_date']}}
				            </li>
				            <li class="list-group-item">
				            	Service end date: {{$payment['service_end_date']}}
				            </li>
				            <li class="list-group-item">
				            	Payment date: {{$payment['payment_date']}}
				            </li>
				        </ul> 
				        @endforeach
	        		</div>
	        	</div>
	        </div>
        </div>
        <div class="row gy-4">
        	<h3>Intrested Buyer Details</h3>
        	<div class="col-xl-4 col-lg-4 col-4 grid-margin">
        		@foreach ($intrestedBuyersArr as $user)
        	<div class="card">
	      			<div class="card-body">
	        			<h5 class="card-title">Intrested Buyer Detail</h5>
	        			<ul class="list-group">
				            <li class="list-group-item">
				            	Name: {{$user['name']}}
				            </li>
				            <li class="list-group-item">
				            	Email: {{$user['email']}}
				            </li>
				            <li class="list-group-item">
				            	Email verified? @if($user['email_verified'] == 1)
			                    <span class="mdi mdi-check"></span>
			                   @else
			                   <span class="mdi mdi-close"></span>
			                   
			                  @endif
				            </li>
				            <li class="list-group-item">
				            	Phone {{$user['phone']}}
				            </li>
				            <li class="list-group-item">
				            	Phone verified?
				            	@if($user['phone_verified'] == 1)
			                    <span class="mdi mdi-check"></span>
			                   @else
			                   <span class="mdi mdi-close"></span>
			                   
			                  @endif
				            </li>
				            <li>Payment Detail</li>
				            <li>
				            			@foreach ($user['payments'] as $payment)
							        			<ul class="list-group">
										            <li class="list-group-item">
										            	Amount: {{$payment['amount']}}
										            </li>
										            <li class="list-group-item">
										            	Status: {{$payment['status']}}
										            </li>
										            <li class="list-group-item">
										            	Payment from: {{$payment['payment_from']}}
										            </li>
										            <li class="list-group-item">
										            	Payment by: {{$payment['user']}}
										            </li>
										            <li class="list-group-item">
										            	Service start date: {{$payment['service_start_date']}}
										            </li>
										            <li class="list-group-item">
										            	Service end date: {{$payment['service_end_date']}}
										            </li>
										            <li class="list-group-item">
										            	Payment date: {{$payment['payment_date']}}
										            </li>
										        </ul> 
										        @endforeach
				            		
				            </li>
				            <li class="list-group-item">
				            	<a href="{{ route('admin.property.payment', ['service_id'=>$user['pivot_buyer_id'],'service_type'=>'buyer_property']) }}">Make Buyer Payment</a>
				            </li>

				          </ul>
	        		</div>
	        		</div>
	        		@endforeach
	        	</div>
	        </div>
	      </div>
        </div>
      </div>
    </div>
  </div>
</div>



@stop

    
