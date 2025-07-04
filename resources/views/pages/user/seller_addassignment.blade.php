@extends('layout.master')
@section('content')
@php
use App\Http\Controllers\Utils\GeneralUtils;
$selected_category = old('category');
$category_option = GeneralUtils::get_select_option('assignment_category',$selected_category);
$selected_deal_price_unit = old('deal_price_unit');
$ask_price_unit_option = GeneralUtils::get_select_option('ask_price_unit_option',$selected_deal_price_unit);
@endphp
<section class="dashboard-wrap">
   <div class="container">
      <div class="row">
         @include('layout.seller_nav')
         <div class="col-lg-8 col-xl-9">
            <div class="dashboard-details">
               <button class="navToggle2 cta-primary mb-4"><i class="fa-solid fa-sliders"></i> Open Dashboard Nav</button>
               <div class="form-wrap">
                  <header>
                     <h2>Add Assignment</h2>

                  </header>
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
                  <form action="{{ route('user.seller.saveassignment') }}" method="post">
                     @csrf
                     <div class="row">
                        <div class="col-md-6">
                           <div class="field">
                              <label for="category">Category</label>
                              <select id="category" class="form-select" name="category" required="">
                                 <option value="">-Select-</option>
                                 {!!$category_option!!}
                              </select>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="field">
                              <label for="subject">Subject</label>
                              <input id="subject" type="text" class="form-control" name="subject" placeholder="Subject" required="" value="{{ old('subject') }}">
                           </div>
                        </div>
                        <div class="col-md-12">
                           <div class="field">
                              <label for="description">Brief of the work</label>
                               <b>Please do not put your email or mobile number in this field</b>

                              <textarea id="description" class="form-control" name="description" placeholder="Brief of the work" required="">{{ old('description') }}</textarea>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="field">
                              <label for="deal_price">Minimum Deal Value</label>
                              <input id="deal_price" type="number" class="form-control" name="deal_price" placeholder="Minimum Deal Value" required="" value="{{ old('deal_price') }}">
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="input-group field">

                              <label>&nbsp;</label>
                              <select id="deal_price_unit" class="form-select" name="deal_price_unit" required>
                                 <option value="">-Select-</option>
                                 {!!$ask_price_unit_option!!}

                              </select>
                           </div>
                        </div>
                        <div class="col-md-12">
                           <fieldset class="scheduler-border">
                              <legend class="scheduler-border">Agree to Terms and Conditions</legend>
                              <div class="row">
                                 <div class="col-md-12">
                                    <label>
                                       <input type="checkbox" required class="terms" id="term1">

                                       I agree that the platform fee paid to Companydeals is non-refundable, even if no deal happens. The fee is only for access to listings and sharing contacts, not for ensuring any deal or response. There is no guarantee of buyer interest, seller action, or a successful transaction.


                                    </label><br>

                                    <label>
                                       <input type="checkbox" required class="terms" id="term2"> I am solely responsible for checking the authenticity and legality of all parties and documents involved. Companydeals will not be held liable for any loss, fraud, or failure by any party in the deal.
                                       Any issue found before or after the deal is entirely my responsibility.


                                    </label><br>

                                    <label>
                                       <input type="checkbox" required class="terms" id="term3">I allow Companydeals to share my name, phone number, and email with the other party after payment. I accept that all communication and transactions are at my own risk. I will not hold Companydeals responsible for any dispute, miscommunication, or failed deal.
                                       <!-- <b>All the entries of Companies, Properties, Trademarks and Assignments (in Seller & buyer Section both) must reflect on top as and when edited.</b> -->

                                    </label><br>
                                    <p class="text-danger d-none">You must agree to all terms before submitting.</p>
                                 </div>
                              </div>

                           </fieldset>
                        </div>

                        <div class="col-xl-12 mt-2">
                           <button class="cta-primary" type="submit">Submit</button>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
@endsection