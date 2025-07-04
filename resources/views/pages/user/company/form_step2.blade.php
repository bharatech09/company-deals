@php
    use App\Http\Controllers\Utils\GeneralUtils;
    $turnover_base_yr = old('turnover_base_yr',isset($companyData)? $companyData->turnover_year1:'');
    $year_of_incorporation = $companyData->year_of_incorporation;
    $turnover_base_yr_option = GeneralUtils::get_base_yr_option($turnover_base_yr,$year_of_incorporation);

    


@endphp
@extends('layout.master')
@section('content')
<section class="dashboard-wrap">
<div class="container">
    <div class="row">
        @include('layout.seller_nav')
        <div class="col-lg-8 col-xl-9">
            <div class="dashboard-details">
            <button class="navToggle2 cta-primary mb-4"><i class="fa-solid fa-sliders"></i> Open Dashboard Nav</button>
            <div class="form-wrap">
            <header>
                <h2>COMPANY FINANCIAL INFORMATIONS</h2>
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
            <form id="frmstep2" action="{{ route('user.seller.companyform.savestep2')}}" method="post" id="companyFrm2">
            <input type="hidden" id="company_id" name="id" value="{{$companyData->id}}">  
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="field">
                        <label for="turnover_base_yr">Latest Audited Financial year</label>
                      <select id="turnover_base_yr" class="form-select" name="turnover_base_yr" required="">
                            <option value="">-Select-</option>
                            {!!$turnover_base_yr_option!!}
                        </select>
                    </div>
                </div>
            </div>
            <div class="row" id="TurnoverDiv">
            </div>
            
                <div class="row">
                    <div class="col-md-6">
                        <a class="cta-primary" href="{{ route('user.seller.companyform.showstep1',['id' => $companyData->id])}}">Previous</a>
                    </div>
                    <div class="col-md-6">
                    <button class="cta-primary float-end" type="submit">Next</button>
                    </div>
                </div>

            
            </form>
        </div>   
    </div>
</div>
</div>
</div>
</section>
@push('plugin-scripts')
<script type="text/javascript">
    function getTurnoverField(){
        $('#TurnoverDiv').html("");
        var turnover_base_yr = $("#turnover_base_yr").val();
        var id = $("#company_id").val(); 
        $.ajax({
            url: '{{ route("user.seller.companyform.additionalstep2") }}',
            method: 'POST',
            async: false,
            data: {"_token": "{{ csrf_token() }}", "turnover_base_yr": turnover_base_yr,"id": id},
            success: function(html) {  
                    $('#TurnoverDiv').html(html);
            }
        });
    }
$(document).ready(function() {
    $('#frmstep2').on('change','#turnover_base_yr', function() {
        getTurnoverField();
    });
    getTurnoverField();
});
</script>
@endpush
@endsection