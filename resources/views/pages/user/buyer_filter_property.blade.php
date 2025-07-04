@extends('layout.master')
@section('content')
<section class="dashboard-wrap">
    <div class="container">
        <div class="row">
        @include('layout.buyer_nav')
        <div class="col-lg-8 col-xl-9">
            <div class="dashboard-details">
                <button class="navToggle2 cta-primary mb-4"><i class="fa-solid fa-sliders"></i> Open
                    Dashboard Nav</button>
                <div class="form-wrap">
                    <header>
                        <h2>Properties search</h2>
                    </header>
        @php
            $states_option = Config::get('selectoptions.states');
            $property_type_option = Config::get('selectoptions.property_type_option');
        @endphp
        <form id="propery_filter_form">
            @csrf
          <div class="row">
            
            <div class="col-md-6 col-xl-3">
                <div class="field">
                <label for="state">State</label>
                <select id="state" class="form-select" name="state" required="">
                    <option value="">-Select-</option>
                        @foreach ($states_option as $key => $stateName)
                            <option value="{{$key}}" {{ old("state") == $key ? "selected" : "" }}>{{$stateName}}</option>
                        @endforeach
                </select>
            </div>
        </div>
            <div class="col-md-6 col-xl-3">
                <div class="field">
                <label for="space">Space</label>
                <div class="slider-range">
                      <div class="container my-5">
                          <div id="space_slider" class="my-4"></div>Sq ft.
                          <input id="space_min" type="hidden" name="space_min" value="">
                            <input id="space_max" type="hidden" name="space_max" value="">
                        </div>
                    </div> 
            </div>
        </div>
            <div class="col-md-6 col-xl-3">
                <div class="field">
                <label for="type">Type</label>
                    <select id="type" class="form-select" name="type" required="" value="{{ old('type') }}">
                        <option value="">-Select-</option>
                        @foreach ($property_type_option as $key => $eachOption)
                            <option value="{{$key}}" {{ old("type") == $key ? "selected" : "" }}>{{$eachOption}}</option>
                        @endforeach
                    </select>
            </div>
        </div>
            <div class="col-md-6 col-xl-3">
                <div class="field">
                <label for="type">Ask price</label>
                <div class="slider-range">
            <div class="container my-5">

              <div id="ask_price_slider" class="my-4"></div>
              <input id="ask_price_min" type="hidden" name="ask_price_min" value="">
                <input id="ask_price_max" type="hidden" name="ask_price_max" value="">
            </div>
        </div>

            </div>
            
          </div>
      </div>

          </form>
        </div>
        <div class="buyer-seller-details">
            <div class="row" id="property_filter">
            </div>
        </div>


        </div>
        
      
    </div>
</div>
</div>
</section>

@push('plugin-styles')
    <link  rel="stylesheet" href="{{asset('/frontendassets/css/nouislider.min.css')}}">
@endpush
@push('plugin-scripts')
<script src="{{asset('/frontendassets/js/nouislider.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
    function fetchProperties() {
        $.ajax({
            url: '{{ route("user.buyer.property_filter_ajax") }}',
            method: 'POST',
            async: false, 
            data: $('#propery_filter_form').serialize(),
            success: function(html) {
                $('#property_filter').html(html);
            }
        });
    }
    // Initial fetch on page load
    fetchProperties();

    // Trigger filtering on form changes
    $('#propery_filter_form').on('change','#state, #type', function() { 
        fetchProperties();
    //    resetPriceSlider();
    });
    
    $('#propery_filter_form').on('change','#space_min, #space_max', function() { 
        fetchProperties();
    });
    $('#propery_filter_form').on('change','#ask_price_min, #ask_price_max', function() { 
        fetchProperties();
    });
    
    
});
</script>
<script type="text/javascript">
    function genrateSliders(slider,min,max,minh,maxh){
        minh.value = Math.round(min);
        maxh.value = Math.round(max);
        noUiSlider.create(slider, {
            start: [min, max], 
            connect: true,  
            range: {
              'min': min,
              'max': max
            },
            format:{
              from: (value) => {
                return parseInt(value);
              },
              to: (value) => {
                return parseInt(value);
              }
            },
            step: 1, // Slider step size
            tooltips: [true, true], // Show tooltips for both handles
        }); 
    
        slider.noUiSlider.on('change', function (values) {
            minh.value = Math.round(values[0]);
            maxh.value = Math.round(values[1]);
            $(minh).trigger("change");

        });

    }
    function resetPriceSlider(){
        var ask_price_slider = document.getElementById('ask_price_slider');
        ask_price_slider.noUiSlider.destroy();
        genratePriceSlider();

    }
    function genratePriceSlider(){
        var ask_price_slider = document.getElementById('ask_price_slider');
        var ask_price_slider_min = $("#property_filter #data_price_min").val();
        var ask_price_slider_max = $("#property_filter #data_price_max").val();
        var ask_price_min_h = document.getElementById('ask_price_min');
        var ask_price_max_h = document.getElementById('ask_price_max');
        genrateSliders(ask_price_slider,parseInt(ask_price_slider_min),parseInt(ask_price_slider_max),ask_price_min_h,ask_price_max_h);

    }

    function genrateSpaceSlider(){
        var space_slider = document.getElementById('space_slider');
        var space_slider_min = $("#property_filter #data_space_min").val();
        var space_slider_max = $("#property_filter #data_space_max").val();
        var space_min_h = document.getElementById('space_min');
        var space_max_h = document.getElementById('space_max');
        genrateSliders(space_slider,parseInt(space_slider_min),parseInt(space_slider_max),space_min_h,space_max_h);

    }
    $(document).ready(function() {
        genratePriceSlider();
        genrateSpaceSlider();
    });
</script>
@endpush
@endsection