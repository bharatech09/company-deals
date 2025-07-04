@extends('layout.master')
@section('content')
@php
    use App\Http\Controllers\Utils\GeneralUtils;
    $selected_class = old('class_no');
    $class_option = GeneralUtils::get_class_option($selected_class);
@endphp
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
                        <h2>Search for NOC of trademark</h2>
                    </header>
                <form id="trademark_filter_form">
            @csrf
          <div class="row">
            <div class="col-md-6 col-xl-3">
                <div class="field">
                    <label for="state">Class</label>
                    <select id="class_no" class="form-select" name="class_no">
                        <option value="">-Select-</option>
                            {!!$class_option!!}
                    </select>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="field">
                <label for="type">Ask price</label>
            <div class="container my-5">

              <div id="ask_price_slider" class="my-4"></div>
              <input id="ask_price_min" type="hidden" name="ask_price_min" value="">
                <input id="ask_price_max" type="hidden" name="ask_price_max" value="">
            </div>
            </div>
            
          </div>
      </div>
          </form>
        </div>
        <div class="buyer-seller-details">
        <div class="row" id="trademark_filter">

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
            url: '{{ route("user.buyer.noctrademark_filter_ajax") }}',
            method: 'POST',
            async: false, 
            data: $('#trademark_filter_form').serialize(),
            success: function(html) {
                $('#trademark_filter').html(html);
            }
        });
    }
    // Initial fetch on page load
    fetchProperties();

    // Trigger filtering on form changes
    $('#trademark_filter_form').on('change','#class_no', function() { 
        fetchProperties();
    //    resetPriceSlider();
    });
    
    
    $('#trademark_filter_form').on('change','#ask_price_min, #ask_price_max', function() { 
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
        var ask_price_slider_min = $("#trademark_filter #data_price_min").val();
        var ask_price_slider_max = $("#trademark_filter #data_price_max").val();
        var ask_price_min_h = document.getElementById('ask_price_min');
        var ask_price_max_h = document.getElementById('ask_price_max');
        genrateSliders(ask_price_slider,parseInt(ask_price_slider_min),parseInt(ask_price_slider_max),ask_price_min_h,ask_price_max_h);

    }
    $(document).ready(function() {
        genratePriceSlider();
    });
</script>
@endpush
@endsection