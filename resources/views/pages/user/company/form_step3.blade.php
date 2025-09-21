@php
use App\Http\Controllers\Utils\GeneralUtils;
$selected_roc_status = old('roc_status',isset($companyData)? $companyData->roc_status:'');
$roc_status_option = GeneralUtils::get_select_option('compliance_status1',$selected_roc_status);
$selected_roc_year = old('roc_year',isset($companyData)? $companyData->roc_year:'');
$roc_year_option = GeneralUtils::get_compliance_year_option($selected_roc_year, $companyData->year_of_incorporation);

$selected_income_tax_status = old('income_tax_status',isset($companyData)? $companyData->income_tax_status:'');
$income_tax_status_option = GeneralUtils::get_select_option('compliance_status1',$selected_income_tax_status);
$selected_income_tax_year = old('income_tax_year',isset($companyData)? $companyData->income_tax_year:'');
$income_tax_year_option = GeneralUtils::get_compliance_year_option($selected_income_tax_year, $companyData->year_of_incorporation);

$selected_gst_status = old('gst_status',isset($companyData)? $companyData->gst_status:'');
$gst_status_option = GeneralUtils::get_select_option('compliance_status2',$selected_gst_status , $companyData->have_gst);
$selected_gst_year = old('gst_year',isset($companyData)? $companyData->gst_year:'');
$gst_year_option = GeneralUtils::get_compliance_year_option($selected_gst_year, $companyData->year_of_incorporation);

$selected_rbi_status = old('rbi_status',isset($companyData)? $companyData->rbi_status:'');
$rbi_status_option = GeneralUtils::get_select_option('compliance_status2',$selected_rbi_status);
$selected_rbi_year = old('rbi_year',isset($companyData)? $companyData->rbi_year:'');
$rbi_year_option = GeneralUtils::get_compliance_year_option($selected_rbi_year, $companyData->year_of_incorporation);

$selected_fema_status = old('fema_status',isset($companyData)? $companyData->fema_status:'');
$fema_status_option = GeneralUtils::get_select_option('compliance_status2',$selected_fema_status);
$selected_fema_year = old('fema_year',isset($companyData)? $companyData->fema_year:'');
$fema_year_option = GeneralUtils::get_compliance_year_option($selected_fema_year, $companyData->year_of_incorporation);

$selected_sebi_status = old('sebi_status',isset($companyData)? $companyData->sebi_status:'');
$sebi_status_option = GeneralUtils::get_select_option('compliance_status2',$selected_sebi_status);
$selected_sebi_year = old('sebi_year',isset($companyData)? $companyData->sebi_year:'');
$sebi_year_option = GeneralUtils::get_compliance_year_option($selected_sebi_year,$companyData->year_of_incorporation);

$selected_stock_exchange_status = old('stock_exchange_status',isset($companyData)? $companyData->stock_exchange_status:'');
$stock_exchange_status_option = GeneralUtils::get_select_option('compliance_status2',$selected_stock_exchange_status);
$selected_stock_exchange_year = old('stock_exchange_year',isset($companyData)? $companyData->stock_exchange_year:'');
$stock_exchange_year_option = GeneralUtils::get_compliance_year_option($selected_stock_exchange_year,$companyData->year_of_incorporation);




$selected_certicate_status = old('certicate_status',isset($companyData)? $companyData->certicate_status:'');
$certificate_status_option = GeneralUtils::get_select_option('compliance_status2', $selected_certicate_status);
$selected_certificate_year = old('stock_exchange_year',isset($companyData)? $companyData->certicate_year:'');
$certificate_year_option = GeneralUtils::get_compliance_year_option($selected_certificate_year, $companyData->year_of_incorporation);







$selected_isin_status = old('isin_status',isset($companyData)? $companyData->isin_status:'');
$isin_status_option = GeneralUtils::get_select_option('compliance_status2', $selected_isin_status);
$selected_isin_year = old('isin_year',isset($companyData)? $companyData->isin_year:'');
$isin_year_option = GeneralUtils::get_compliance_year_option($selected_isin_year, $companyData->isin_year);




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
                            <h2>COMPANY COMPLIANCE STATUS</h2>
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
                        <form id="frmstep3" action="{{ route('user.seller.companyform.savestep3')}}" method="post">
                            <input type="hidden" name="id" value="{{$companyData->id}}">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">ROC <span class="text-danger">*</span></legend>
                                        <div class="field">


                                            <select id="roc_status" class="form-select" name="roc_status" required>
                                                <option value="">-Select-</option>
                                                {!!$roc_status_option!!}
                                            </select>
                                        </div>
                                        <div class="field d-none">
                                            <select id="roc_year" class="form-select" name="roc_year">
                                                <option value="">-Select-</option>
                                                {!!$roc_year_option!!}
                                            </select>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border"> Income Tax <span class="text-danger">*</span></legend>
                                        <div class="field">


                                            <select id="income_tax_status" class="form-select" name="income_tax_status" required>
                                                <option value="">-Select-</option>
                                                {!!$income_tax_status_option!!}
                                            </select>
                                        </div>
                                        <div class="field d-none">
                                            <select id="income_tax_year" class="form-select" name="income_tax_year">
                                                <option value="">-Select-</option>
                                                {!!$income_tax_year_option!!}
                                            </select>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">GST <span class="text-danger">*</span></legend>
                                        <div class="field">


                                            <select id="gst_status" class="form-select" name="gst_status" required>
                                                <option value="">-Select-</option>
                                                {!!$gst_status_option!!}
                                            </select>
                                        </div>
                                        <div class="field d-none">
                                            <select id="gst_year" class="form-select" name="gst_year">
                                                <option value="">-Select-</option>
                                                {!!$gst_year_option!!}
                                            </select>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">RBI <span class="text-danger">*</span></legend>
                                        <div class="field">


                                            <select id="rbi_status" required class="form-select" name="rbi_status">
                                                <option value="">-Select-</option>
                                                {!!$rbi_status_option!!}
                                            </select>
                                        </div>
                                        <div class="field d-none">
                                            <select id="rbi_year" class="form-select" name="rbi_year">
                                                <option value="">-Select-</option>
                                                {!!$rbi_year_option!!}
                                            </select>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">FEMA <span class="text-danger">*</span></legend>
                                        <div class="field">


                                            <select id="fema_status" required class="form-select" name="fema_status">
                                                <option value="">-Select-</option>
                                                {!!$fema_status_option!!}
                                            </select>
                                        </div>
                                        <div class="field d-none">
                                            <select id="fema_year" class="form-select" name="fema_year">
                                                <option value="">-Select-</option>
                                                {!!$fema_year_option!!}
                                            </select>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">SEBI <span class="text-danger">*</span></legend>
                                        <div class="field">


                                            <select id="sebi_status" required class="form-select" name="sebi_status">
                                                <option value="">-Select-</option>
                                                {!!$sebi_status_option!!}
                                            </select>
                                        </div>
                                        <div class="field d-none">
                                            <select id="sebi_year" class="form-select" name="sebi_year">
                                                <option value="">-Select-</option>
                                                {!!$sebi_year_option!!}
                                            </select>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Stock Exchange <span class="text-danger">*</span></legend>
                                        <div class="field">


                                            <select id="stock_exchange_status" required class="form-select" name="stock_exchange_status">
                                                <option value="">-Select-</option>
                                                {!!$stock_exchange_status_option!!}
                                            </select>
                                        </div>
                                        <div class="field d-none">
                                            <select id="stock_exchange_year" class="form-select" name="stock_exchange_year">
                                                <option value="">-Select-</option>
                                                {!!$stock_exchange_year_option!!}
                                            </select>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border"> 80G/12A Certificate <span class="text-danger">*</span></legend>
                                        <div class="field">


                                            <select id="certicate_status" required class="form-select" name="certicate_status">
                                                <option value="">-Select-</option>
                                                {!!$certificate_status_option!!}
                                            </select>
                                        </div>
                                        <div class="field d-none">
                                            <select id="certicate_year" class="form-select" name="certicate_year">
                                                <option value="">-Select-</option>
                                                {!!$certificate_year_option!!}
                                            </select>
                                        </div>
                                    </fieldset>
                                </div>
                                 <div class="col-md-6">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border"> ISIN <span class="text-danger">*</span></legend>
                                        <div class="field">


                                            <select id="isin_status" required class="form-select" name="isin_status">
                                                <option value="">-Select-</option>
                                                {!!$isin_status_option!!}
                                            </select>
                                        </div>
                                        <div class="field d-none">
                                            <select id="isin_year" class="form-select" name="isin_year">
                                                <option value="">-Select-</option>
                                                {!!$isin_year_option!!}
                                            </select>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    @if ($companyData->year_of_incorporation == date('Y'))
                                    <a class="cta-primary" href="{{ route('user.seller.companyform.showstep1',['id' => $companyData->id])}}">Previous</a>
                                    @else
                                    <a class="cta-primary" href="{{ route('user.seller.companyform.showstep2',['id' => $companyData->id])}}">Previous</a>
                                    @endif
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
    function showHideYearDropDown() {
        $(".form-wrap form fieldset").each(function(index, element) {
            let status = $(this).find('select:eq(0)');
            let year = $(this).find('select:eq(1)');

            if (status.val() == "Updated upto") {
                year.parent("div").removeClass("d-none");
            } else {
                year.parent("div").addClass("d-none");
            }
        });
    }
    $(document).ready(function() {
        showHideYearDropDown();
        $('select').change(function() {
            showHideYearDropDown();
        });

        $("#frmstep3").on("submit", function(event) {
            let isValid = true;
            $("div.text-danger").remove();
            $("select").removeClass("text-danger");
            $(".form-wrap form fieldset").each(function(index, element) {
                let status = $(this).find('select:eq(0)');
                let year = $(this).find('select:eq(1)');


                if (status.val() == "Updated upto" && year.val() == "") {
                    isValid = false;
                    year.addClass("text-danger");
                    year.parent("div").after('<div class="text-danger">Please select year </div>');

                }

            });
            if (!isValid) {
                event.preventDefault();
            }

        });

    });
</script>
@endpush
@endsection