@php
    use App\Http\Controllers\Utils\GeneralUtils;
    
    $selected_type_of_entity = old('type_of_entity',isset($companyData)? $companyData->type_of_entity:'');
    $type_of_entity = GeneralUtils::get_type_of_entity($selected_type_of_entity);

    $selected_roc = old('roc',isset($companyData)? $companyData->roc:'');
    $roc_option = GeneralUtils::get_select_option('roc',$selected_roc);
    
    $selected_yoi = old('year_of_incorporation',isset($companyData)? $companyData->year_of_incorporation:'');
    $year_of_incor_option = GeneralUtils::get_year_of_incor_option($selected_yoi);
   
    $selected_industry = old('industry',isset($companyData)? $companyData->industry:'');
    $industry_option = GeneralUtils::get_select_option('industry',$selected_industry);
 
    $selected_demat_shareholding = old('demat_shareholding',isset($companyData)? $companyData->demat_shareholding:'');
    $demat_shareholding_option = GeneralUtils::get_percentage_option($selected_demat_shareholding);

    $selected_physical_shareholding = old('physical_shareholding',isset($companyData)? $companyData->physical_shareholding:'');
    $physical_shareholding_option = GeneralUtils::get_percentage_option($selected_physical_shareholding);
    
    $selected_authorised_capital_unit = old('authorised_capital_unit',isset($companyData)? $companyData->authorised_capital_unit:'');
    $authorised_capital_unit_option = GeneralUtils::get_select_option('price_unit',$selected_authorised_capital_unit);

    $selected_paidup_capital_unit = old('paidup_capital_unit',isset($companyData)? $companyData->paidup_capital_unit:'');
    $paidup_capital_unit_option = GeneralUtils::get_select_option('price_unit',$selected_paidup_capital_unit);

    $selected_activity_code = old('activity_code',isset($companyData)? $companyData->activity_code:'');
    $activity_code_option = GeneralUtils::get_percentage_option($selected_activity_code);

    $showGst = "d-none";
    $haveGst = old('have_gst',isset($companyData)? $companyData->have_gst:'');
    if($haveGst == 'Yes'){
        $showGst = "";
    }
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
                <h2>COMPANY GENERAL INFORMATIONS</h2>
                <p  style="color: black; font-weight: 700;">(Please provide accurate information to secure better and faster deals.)</p>
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
            <form action="{{ route('user.seller.companyform.savestep1')}}" id="companyFrm" method="post">
            <input type="hidden" name="id" id="company_id" value="{{isset($companyData)? $companyData->id:''}}">
             
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="field">
                            <label for="type_of_entity">Type Of Entity <span class="text-danger">*</span></label>
                            @if ($companyData && $companyData->status == 'active')
                                {{$companyData->type_of_entity}}
                                
                                <input type="hidden" id="type_of_entity" value="{{$companyData->type_of_entity}}">
                            @else
                                <select id="type_of_entity" class="form-select" name="type_of_entity" required>
                                 <option value="">-Select-</option>
                                {!!$type_of_entity!!}
                                </select>
                            @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="field">
                        <label for="roc">ROC <span class="text-danger">*</span></label>
                        @if ($companyData && $companyData->status == 'active')
                            {{$companyData->roc}}
                        @else
                            <select id="roc" class="form-select" name="roc" required>
                                <option value="">-Select-</option>
                                {!!$roc_option!!}
                            </select>
                        @endif
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="field input-group">
                        <label for="name"> Name of Company/LLP <span class="text-danger">*</span></label>
                        @if ($companyData && $companyData->status == 'active')
                            {{$companyData->name}} {{$companyData->name_prefix}}
                        @else
                        <input id="name"  type="text" class="form-control" name="name" placeholder="Put a Dummy Name" required value="{{ old('name',isset($companyData)? $companyData->name:'')}}" aria-describedby="name_prefix_span"><span class="input-group-text" id="name_prefix_span">{{ old('name_prefix',isset($companyData)? $companyData->name_prefix:'')}}</span><button class="cta-primary float-end" 
id="check_availability" type="button">Check Availability</button>
<input type="hidden" id="name_prefix" name="name_prefix" value="{{ old('name_prefix',isset($companyData)? $companyData->name_prefix:'')}}">
                        @endif
                    </div>
                </div>
            <!--
                <div class="col-md-6">
                    <div class="field">
                        <label for="cin_llpin"> CIN/LLPIN <span class="text-danger">*</span></label>
                        <input id="cin_llpin"  type="text" class="form-control" name="cin_llpin" placeholder="CIN/LLPIN" required value="{{ old('cin_llpin',isset($companyData)? $companyData->cin_llpin:'')}}">
                    </div>
                </div>
              -->  
                
                <div class="col-md-6">
                    <div class="field">
                        <label for="year_of_incorporation">Year of Incorporation <span class="text-danger">*</span></label>
                         @if ($companyData && $companyData->status == 'active')
                            {{$companyData->year_of_incorporation}}
                        @else
                            <select id="year_of_incorporation" class="form-select" name="year_of_incorporation" required>
                                <option value="">-Select-</option>
                                {!!$year_of_incor_option!!}
                            </select>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="field">
                        <label for="industry">Industry <span class="text-danger">*</span></label>
                        @if ($companyData && $companyData->status == 'active')
                            {{$companyData->industry}}
                        @else
                            <select id="industry" class="form-select" name="industry" required>
                                <option value="">-Select-</option>
                                {!!$industry_option!!}
                            </select>
                        @endif
                        
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="field">
                        <label for="have_gst">Have GST? <span class="text-danger">*</span></label>
                        <select id="have_gst" class="form-select" name="have_gst" required>
                            <option value="">-Select-</option>
                            <option value="Yes" {{ $haveGst  == 'Yes' ? "selected" : "" }}>Yes</option>
                            <option value="No" {{ $haveGst  == 'No' ? "selected" : "" }}>No</option>
                        </select>
                    </div>
                </div>
            <!--
                 <div class="col-md-6 {{$showGst}}">
                    <div class="field">
                        <label for="gst"> GST</label>
                        <input id="gst"  type="text" class="form-control"  name="gst" placeholder="GST" value="{{ old('gst',isset($companyData)? $companyData->gst:'')}}">
                    </div>
                </div>
            -->
                <div class="col-md-6">
                    <div class="field">
                        <label for="no_of_directors">No. of Directors & KMPs <span class="text-danger">*</span></label>
                        <input id="no_of_directors"  type="number" min="1" max="999" step="1" required class="form-control onlynumber threedigit" name="no_of_directors" placeholder="No. of Directors & KMPs" value="{{ old('no_of_directors',isset($companyData)? $companyData->no_of_directors:'')}}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="field">
                        <label for="no_of_promoters">No. of Promoters <span class="text-danger">*</span></label>
                        <input id="no_of_promoters"  type="number" min="0" max="999" required class="form-control onlynumber threedigit" name="no_of_promoters" placeholder="No. of Promoters" value="{{ old('no_of_promoters',isset($companyData)? $companyData->no_of_promoters:'')}}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="field input-group">
                        <label for="activity_code">Activity Code</label>
                        <input id="activity_code"  type="number" min="1" max="99"  class="form-control onlynumber threedigit" name="activity_code" placeholder="Activity Code" value="{{ old('activity_code',isset($companyData)? $companyData->activity_code:'')}}">
                    </div>
                </div>
                <div class="col-md-6">
                    <fieldset class="scheduler-border pricewithunit">
                    <legend class="scheduler-border">Authorised capital <span class="text-danger">*</span></legend>
                    <div class="field">
                        
                        <input id="authorised_capital"  type="number" step="1" min="1" class="form-control onlynumber fourdigit" required name="authorised_capital" placeholder="Authorised capital"  value="{{ old('authorised_capital',isset($companyData)? $companyData->authorised_capital:'')}}">
                    </div>
                    <div class="field">
                        
                        <select id="authorised_capital_unit" onchange="showvalue(this.value)" class="form-select" name="authorised_capital_unit" required >
                        <option value="">-Select-</option>
                        {!!$authorised_capital_unit_option!!}
                        </select>
                    </div>
                    </fieldset>
                </div>
                <div class="col-md-6">
                    <fieldset class="scheduler-border pricewithunit">
                    <legend class="scheduler-border">Paid-up capital <span class="text-danger">*</span></legend>
                    <div class="field">
                        
                        <input id="paidup_capital"  type="number" step="1" min="1" class="form-control onlynumber fourdigit" required name="paidup_capital" placeholder="Paid-up capital"  value="{{ old('paidup_capital',isset($companyData)? $companyData->paidup_capital:'')}}">
                    </div>
                    <div class="field">
                        
                        <select id="paidup_capital_unit" class="form-select" name="paidup_capital_unit" required >
                        <option value="">-Select-</option>
                        {!!$paidup_capital_unit_option!!}
                        </select>
                    </div>
                    </fieldset>
                </div>
                
                <div class="col-md-6">
                    <div class="field">
                        <label for="demat_shareholding">Demat Shareholding <span class="text-danger">*</span></label>  
                    <select id="demat_shareholding" onchange="directValue(this.value)" class="form-select" name="demat_shareholding" required>
                        <option value="">-Select-</option>
                        {!!$demat_shareholding_option!!}
                    </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="field">
                        <label for="physical_shareholding">Physical Shareholding <span class="text-danger">*</span></label> 
                        <select id="physical_shareholding" id="physical_shareholding" class="form-select" name="physical_shareholding" required>
                            <option value="">-Select-</option>
                            {!!$physical_shareholding_option!!}
                        </select>
                    </div>
                </div>
                <script>
                    function directValue(value){
                        let remaingvalyue  =  100 - value;
                       $('#physical_shareholding').val(remaingvalyue).change();
                    }
                </script>
                
            </div>
            <div id="additionalDiv" class="row">                 
            </div>
            <div class="row">
                <div class="col-xl-12">
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
function showvalue(value){
        $('#paidup_capital_unit').val(value).change();
}

    function getAdditionalField(){
        $('#additionalDiv').html("");
        var type_of_entity = $("#type_of_entity").val();
        var id = $("#company_id").val(); 
        $.ajax({
            url: '{{ route("user.seller.companyform.additionalstep1") }}',
            method: 'POST',
            async: false,
            data: {"_token": "{{ csrf_token() }}", "type_of_entity": type_of_entity,"id": id},
            success: function(html) {  
                    $('#additionalDiv').html(html);
            }
        });
    }
    function setNamePrefix(){
        var selectedOption = $("#type_of_entity option:selected");
        var suffix = selectedOption.data("suffix");
        console.log(suffix);
        $("#name_prefix_span").html(suffix);
        $("#name_prefix").val(suffix);
    }
$(document).ready(function() {
    $('#companyFrm').on('change','#type_of_entity', function() {
        getAdditionalField();
        setNamePrefix();
    });
    getAdditionalField();
    
    $('#companyFrm').on('click','#check_availability', function() {
        let name = $("#name").val();
        let id = $("#company_id").val();
        $("div.text-danger").remove();
        $("div.text-success").remove();
        $("#name").removeClass("text-danger");
        $.ajax({
            url: '{{ route("user.seller.companyform.check_name") }}',
            method: 'POST',
            async: false,
            data: {"_token": "{{ csrf_token() }}", "name": name,"id": id},
            success: function(msg) { 
               // console.log(msg);
                    if(msg.error=='1'){
                        $("#name").addClass("text-danger");
                        $("#name").parent("div").after('<div class="text-danger">'+msg.message+'</div>');
                    }else if(msg.error=='0'){
                         $("#name").parent("div").after('<div class="text-success">'+msg.message+'</div>');
                    }
            },
        });

    });
/*
    $('#companyFrm').on('change','#have_gst', function() {
        if($(this).val() == 'Yes'){
            $('#companyFrm #gst').closest('div.col-md-6').removeClass("d-none");
        }
        else{
            $('#companyFrm #gst').closest('div.col-md-6').addClass("d-none");
            $('#companyFrm #gst').val("");
        }
    });
*/
});

</script>
@endpush
@endsection