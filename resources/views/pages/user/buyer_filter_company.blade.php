@extends('layout.master')
@section('content')
@php
use App\Http\Controllers\Utils\GeneralUtils;
$selected_type_of_entity = old('type_of_entity');
$type_of_entity = GeneralUtils::get_type_of_entity($selected_type_of_entity);
$selected_roc = old('roc');
$roc_option = GeneralUtils::get_select_option('roc',$selected_roc);
$selected_yoi = old('year_of_incorporation');
$year_of_incor_option = GeneralUtils::get_year_of_incor_option($selected_yoi);
$selected_industry = old('industry');
$industry_option = GeneralUtils::get_select_option('industry',$selected_industry);

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
                            <h2>Search for Companies</h2>
                        </header>
                        <form id="company_filter_form">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 col-xl-4">
                                    <div class="field">
                                        <label for="type_of_entity">Type Of Entity</label>

                                        <select id="type_of_entity" class="form-select" name="type_of_entity" required="">
                                            <option value="">-Select-</option>
                                            {!!$type_of_entity!!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xl-4">
                                    <div class="field">
                                        <label for="roc">ROC</label>
                                        <select id="roc" class="form-select" name="roc" required="">
                                            <option value="">-Select-</option>
                                            {!!$roc_option!!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xl-4">
                                    <div class="field">
                                        <label for="year_of_incorporation">Year of Incorporation</label>

                                        <select id="year_of_incorporation" class="form-select" name="year_of_incorporation" required="">
                                            <option value="">-Select-</option>
                                            {!!$year_of_incor_option!!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xl-4">
                                    <div class="field">
                                        <label for="industry">Industry</label>
                                        <select id="industry" class="form-select" name="industry" required="">
                                            <option value="">-Select-</option>
                                            {!!$industry_option!!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1 col-xl-1">
                                </div>
                                <div class="col-md-6 col-xl-4">
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
                        <div class="row" id="company_filter">

                        </div>
                    </div>


                </div>


            </div>
        </div>
    </div>
</section>

@push('plugin-styles')
<link rel="stylesheet" href="{{asset('/frontendassets/css/nouislider.min.css')}}">
@endpush
@push('plugin-scripts')
<script src="{{asset('/frontendassets/js/nouislider.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        function fetchCompanies() {
            $.ajax({
                url: '{{ route("user.buyer.company_filter_ajax") }}',
                method: 'POST',
                async: false,
                data: $('#company_filter_form').serialize(),
                success: function(html) {
                    $('#company_filter').html(html);
                }
            });
        }
        // Initial fetch on page load
        fetchCompanies();

        // Trigger filtering on form changes
        $('#company_filter_form').on('change', '#type_of_entity,#roc,#ask_price_min, #ask_price_max,#year_of_incorporation,#industry', function() {
            console.log($(this).val());
            fetchCompanies();
        });
    });
</script>
<script type="text/javascript">
    function genrateSliders(slider, min, max, minh, maxh) {
        minh.value = Math.round(min);
        maxh.value = Math.round(max);
        noUiSlider.create(slider, {
            start: [min, max],
            connect: true,
            range: {
                'min': min,
                'max': max
            },
            format: {
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

        slider.noUiSlider.on('change', function(values) {
            minh.value = Math.round(values[0]);
            maxh.value = Math.round(values[1]);
            $(minh).trigger("change");

        });

    }

    function resetPriceSlider() {
        var ask_price_slider = document.getElementById('ask_price_slider');
        ask_price_slider.noUiSlider.destroy();
        genratePriceSlider();

    }

  function genratePriceSlider() {
    var ask_price_slider = document.getElementById('ask_price_slider');
    var ask_price_slider_min = parseInt($("#company_filter #data_price_min").val());
    var ask_price_slider_max = parseInt($("#company_filter #data_price_max").val());
    
    if(isNaN(ask_price_slider_min)){
        ask_price_slider_min=0;
    }

    if (isNaN(ask_price_slider_min) || isNaN(ask_price_slider_max)) {
        console.error("Price slider range values are invalid:", ask_price_slider_min, ask_price_slider_max);
        return; // Skip slider setup
    }

    var ask_price_min_h = document.getElementById('ask_price_min');
    var ask_price_max_h = document.getElementById('ask_price_max');

    genrateSliders(ask_price_slider, ask_price_slider_min, ask_price_slider_max, ask_price_min_h, ask_price_max_h);
}

    $(document).ready(function() {
        genratePriceSlider();
    });
</script>
@endpush
@endsection