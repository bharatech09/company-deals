@extends('layout.master')
@section('content')
@php
    use App\Http\Controllers\Utils\GeneralUtils;
    $selected_type_of_entity = old('type_of_entity');
    $type_of_entity = GeneralUtils::get_type_of_entity($selected_type_of_entity);
    $selected_roc = old('roc');
    $roc_option = GeneralUtils::get_select_option('roc', $selected_roc);
    $selected_yoi = old('year_of_incorporation');
    $year_of_incor_option = GeneralUtils::get_year_of_incor_option($selected_yoi);
    $selected_industry = old('industry');
    $industry_option = GeneralUtils::get_select_option('industry', $selected_industry);
@endphp

<section class="dashboard-wrap">
    <div class="container">
        <div class="row">
            @include('layout.buyer_nav')
            <div class="col-lg-8 col-xl-9">
                <div class="dashboard-details">
                    <button class="navToggle2 cta-primary mb-4">
                        <i class="fa-solid fa-sliders"></i> Open Dashboard Nav
                    </button>

                    <div class="form-wrap">
                        <header>
                            <h2>Search for Companies</h2>
                        </header>
                        <form id="company_filter_form">@csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="type_of_entity">Type Of Entity</label>
                                    <select id="type_of_entity" class="form-select" name="type_of_entity">
                                        <option value="">-Select-</option>
                                        {!! $type_of_entity !!}
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="roc">ROC</label>
                                    <select id="roc" class="form-select" name="roc">
                                        <option value="">-Select-</option>
                                        {!! $roc_option !!}
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="year_of_incorporation">Year of Incorporation</label>
                                    <select id="year_of_incorporation" class="form-select" name="year_of_incorporation">
                                        <option value="">-Select-</option>
                                        {!! $year_of_incor_option !!}
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="industry">Industry</label>
                                    <select id="industry" class="form-select" name="industry">
                                        <option value="">-Select-</option>
                                        {!! $industry_option !!}
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="ask_price">Ask price</label>
                                    <div id="ask_price_slider" class="my-3"></div>
                                    <!-- Visible to JavaScript -->
                                    <input type="hidden" id="ask_price_min" name="ask_price_min" value="0">
                                    <input type="hidden" id="ask_price_max" name="ask_price_max" value="0">
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="buyer-seller-details">
                        <div class="row" id="company_filter">
                            <!-- AJAX company cards will load here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('plugin-styles')
    <link rel="stylesheet" href="{{ asset('/frontendassets/css/nouislider.min.css') }}">
@endpush

@push('plugin-scripts')
<script src="{{ asset('/frontendassets/js/nouislider.min.js') }}"></script>
<script>
    $(document).ready(function () {
        function fetchCompanies() {
            $.ajax({
                url: '{{ route("user.buyer.company_filter_ajax") }}',
                method: 'POST',
                data: $('#company_filter_form').serialize(),
                success: function (html) {
                    $('#company_filter').html(html);
                    setTimeout(resetPriceSlider, 100); // wait for DOM update
                }
            });
        }

        $('#company_filter_form').on('change', '#type_of_entity, #roc, #ask_price_min, #ask_price_max, #year_of_incorporation, #industry', function () {
            fetchCompanies();
        });

        fetchCompanies(); // Initial call
    });

    function genrateSliders(slider, min, max, minInput, maxInput) {
        if (!slider || !minInput || !maxInput) {
            console.warn("Slider or inputs not found.");
            return;
        }

        minInput.value = min;
        maxInput.value = max;

        noUiSlider.create(slider, {
            start: [min, max],
            connect: true,
            range: { min: min, max: max },
            format: {
                from: value => parseInt(value),
                to: value => parseInt(value)
            },
            step: 1,
            tooltips: [true, true],
        });

        slider.noUiSlider.on('change', function (values) {
            minInput.value = parseInt(values[0]);
            maxInput.value = parseInt(values[1]);
            $(minInput).trigger('change');
            $(maxInput).trigger('change');
        });
    }

    function resetPriceSlider() {
        const ask_price_slider = document.getElementById('ask_price_slider');
        if (ask_price_slider.noUiSlider) {
            ask_price_slider.noUiSlider.destroy();
        }

        const min = parseInt($("#company_filter #data_price_min").val());
        const max = parseInt($("#company_filter #data_price_max").val());
        const minInput = document.getElementById('ask_price_min');
        const maxInput = document.getElementById('ask_price_max');
        

        if (!minInput || !maxInput) {
            console.error('ask_price_min or ask_price_max inputs not found.');
            return;
        }

        // min =  0 ;
        // max =2000;
        if (isNaN(min) || isNaN(max)) {
            console.warn("Invalid price range values for slider.");
            return;
        }

        genrateSliders(ask_price_slider, min, max, minInput, maxInput);
    }
</script>
@endpush
@endsection
