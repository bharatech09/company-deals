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
                               {{-- - <div class="col-md-6">
                                    <label for="ask_price">Ask price</label>
                                    <div id="ask_price_slider" class="my-3"></div>
                                    <!-- Visible to JavaScript -->
                                    <input type="hidden" id="ask_price_min" name="ask_price_min" value="0">
                                    <input type="hidden" id="ask_price_max" name="ask_price_max" value="0"> --}}
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
    <style>
        /* Ensure collapse functionality works */
        .collapse:not(.show) {
            display: none;
        }
        
        .collapse.show {
            display: block;
        }
        
        /* Smooth transition for fallback */
        .collapse {
            transition: all 0.3s ease;
        }
        
        /* Ensure toggle buttons are clickable */
        .toggle-more-details2 {
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }
    </style>
@endpush

@push('plugin-scripts')
<script src="{{ asset('/frontendassets/js/nouislider.min.js') }}"></script>
<script>
    // Function to initialize toggle functionality
    function initializeToggleFunctionality() {
        console.log('Initializing toggle functionality...');
        
        // Remove any existing event listeners to prevent duplicates
        document.querySelectorAll('.toggle-more-details2').forEach(function (toggle) {
            // Clone the element to remove all event listeners
            const newToggle = toggle.cloneNode(true);
            toggle.parentNode.replaceChild(newToggle, toggle);
        });

        // Initialize new toggle buttons
        document.querySelectorAll('.toggle-more-details2').forEach(function (toggle) {
            const targetId = toggle.getAttribute('data-bs-target');
            const targetEl = document.querySelector(targetId);

            if (targetEl) {
                console.log('Setting up toggle for:', targetId);
                
                // Check if Bootstrap is available
                if (typeof bootstrap !== 'undefined' && bootstrap.Collapse) {
                    // Create Bootstrap collapse instance
                    const bsCollapse = new bootstrap.Collapse(targetEl, {
                        toggle: false
                    });

                    // Update text when shown
                    targetEl.addEventListener('shown.bs.collapse', function () {
                        toggle.textContent = 'Show Less';
                        toggle.setAttribute('aria-expanded', 'true');
                        console.log('Shown:', targetId);
                    });

                    // Update text when hidden
                    targetEl.addEventListener('hidden.bs.collapse', function () {
                        toggle.textContent = 'Show More';
                        toggle.setAttribute('aria-expanded', 'false');
                        console.log('Hidden:', targetId);
                    });

                    // Handle click events
                    toggle.addEventListener('click', function (e) {
                        e.preventDefault();
                        console.log('Toggle clicked for:', targetId);
                        bsCollapse.toggle();
                    });
                } else {
                    // Fallback: Use CSS classes
                    console.log('Bootstrap not available, using CSS fallback for:', targetId);
                    
                    toggle.addEventListener('click', function (e) {
                        e.preventDefault();
                        const isShown = targetEl.classList.contains('show');
                        
                        if (isShown) {
                            targetEl.classList.remove('show');
                            toggle.textContent = 'Show More';
                            toggle.setAttribute('aria-expanded', 'false');
                        } else {
                            targetEl.classList.add('show');
                            toggle.textContent = 'Show Less';
                            toggle.setAttribute('aria-expanded', 'true');
                        }
                        console.log('Toggle clicked (fallback) for:', targetId, 'isShown:', !isShown);
                    });
                }
            } else {
                console.warn('Target element not found:', targetId);
            }
        });
        
        console.log('Toggle functionality initialized for', document.querySelectorAll('.toggle-more-details2').length, 'elements');
    }

    $(document).ready(function () {
        function fetchCompanies() {
            $.ajax({
                url: '{{ route("user.buyer.company_filter_ajax") }}',
                method: 'POST',
                data: $('#company_filter_form').serialize(),
                success: function (html) {
                    $('#company_filter').html(html);
                    setTimeout(resetPriceSlider, 100); // wait for DOM update
                    // Initialize toggle functionality after content loads
                    setTimeout(initializeToggleFunctionality, 150);
                    // Additional initialization with longer delay as fallback
                    setTimeout(initializeToggleFunctionality, 500);
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
