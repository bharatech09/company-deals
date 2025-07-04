@extends('layout.master')
@section('content')
@php
    use App\Http\Controllers\Utils\GeneralUtils;
    $selected_category = old('category');
    $category_option = GeneralUtils::get_select_option('assignment_category',$selected_category);
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
                        <h2>Search for Assignments</h2>
                    </header>
                <form id="assignment_filter_form">
            @csrf
          <div class="row">
            <div class="col-md-6 col-xl-3">
                <div class="field">
                    <label for="state">Category</label>
                    <select id="category" class="form-select" name="category">
                        <option value="">-Select-</option>
                            {!!$category_option!!}
                    </select>
                </div>
            </div>
      </div>
          </form>
        </div>
        <div class="buyer-seller-details">
        <div class="row" id="assignments_filter">

        </div>
        </div>


        </div>
        
      
    </div>
</div>
</div>
</section>
@push('plugin-scripts')
<script type="text/javascript">
    $(document).ready(function() {
    function fetchAssignments() {
        $.ajax({
            url: '{{ route("user.buyer.assignment_filter_ajax") }}',
            method: 'POST',
            async: false, 
            data: $('#assignment_filter_form').serialize(),
            success: function(html) {
                $('#assignments_filter').html(html);
            }
        });
    }
    // Initial fetch on page load
    fetchAssignments();

    // Trigger filtering on form changes
    $('#assignment_filter_form').on('change','#category', function() { 
        fetchAssignments();
    //    resetPriceSlider();
    });
});
</script>
@endpush
@endsection