@php
use App\Http\Controllers\Utils\GeneralUtils;
@endphp

@if($turnover_base_yr > 0)
@php

$selected_authorized_capital_unit = old('authorized_capital_unit',optional($companyData)->authorized_capital_unit
        ?? optional($companyData)->authorised_capital_unit
);
$authorized_capital_unit_option = GeneralUtils::get_select_option('price_unit',$selected_authorized_capital_unit,'',$companyData->authorised_capital_unit);





// authorised 



$selected_paid_up_capital_unit = old('paid_up_capital_unit',optional($companyData)->paidup_capital_unit ?? optional($companyData)->paid_up_capital_unit);
$paid_up_capital_unit_option = GeneralUtils::get_select_option('price_unit',$selected_paid_up_capital_unit,'',optional($companyData)->paidup_capital_unit ?? optional($companyData)->paid_up_capital_unit);

$selected_net_worth_unit = old('net_worth_unit',isset($companyData)? $companyData->net_worth_unit:'');
$net_worth_unit_option = GeneralUtils::get_select_option('price_unit',$selected_net_worth_unit,'',$companyData->authorised_capital_unit);

$selected_reserve_unit = old('reserve_unit',isset($companyData)? $companyData->reserve_unit:'');
$reserve_unit_option = GeneralUtils::get_select_option('price_unit',$selected_reserve_unit,'',$companyData->authorised_capital_unit);

$selected_secured_creditors_unit = old('secured_creditors_unit',isset($companyData)? $companyData->secured_creditors_unit:'');
$secured_creditors_unit_option = GeneralUtils::get_select_option('price_unit',$selected_secured_creditors_unit,'',$companyData->authorised_capital_unit);

$selected_unsecured_creditors_unit = old('unsecured_creditors_unit',isset($companyData)? $companyData->unsecured_creditors_unit:'');
$unsecured_creditors_unit_option = GeneralUtils::get_select_option('price_unit',$selected_unsecured_creditors_unit,'',$companyData->authorised_capital_unit);

$selected_land_building_unit = old('land_building_unit',isset($companyData)? $companyData->land_building_unit:'');
$land_building_unit_option = GeneralUtils::get_select_option('price_unit',$selected_land_building_unit,'',$companyData->authorised_capital_unit);

$selected_plant_machinery_unit = old('plant_machinery_unit',isset($companyData)? $companyData->plant_machinery_unit:'');
$plant_machinery_unit_option = GeneralUtils::get_select_option('price_unit',$selected_plant_machinery_unit,'',$companyData->authorised_capital_unit);

$selected_investment_unit = old('investment_unit',isset($companyData)? $companyData->investment_unit:'');
$investment_unit_option = GeneralUtils::get_select_option('price_unit',$selected_investment_unit,'',$companyData->authorised_capital_unit);

$selected_debtors_unit = old('debtors_unit',isset($companyData)? $companyData->debtors_unit:'');
$debtors_unit_option = GeneralUtils::get_select_option('price_unit',$selected_debtors_unit,'',$companyData->authorised_capital_unit);

$selected_cash_bank_unit = old('cash_bank_unit',isset($companyData)? $companyData->cash_bank_unit:'');
$cash_bank_unit_option = GeneralUtils::get_select_option('price_unit',$selected_cash_bank_unit,'',$companyData->authorised_capital_unit);
$year_of_incorporation = $companyData->year_of_incorporation;

@endphp
<h3>Turnover </h3>
@foreach ($turnoverData as $yr => $eachTurnover)
@php
if($year_of_incorporation >= $yr ){
break;
}
$turnover_unit_option = GeneralUtils::get_select_option('price_unit',$eachTurnover['turnoverUnit'] ,'',$companyData->authorised_capital_unit );
$required = "";
$requiredstar = "";
if($turnover_base_yr == $yr){
$required = "required";
$requiredstar = '<span class="text-danger">*</span>';
}
@endphp

<div class="col-md-6">
    <fieldset class="scheduler-border pricewithunit">
        <legend class="scheduler-border">Turnover for year {{$yr}} {!!$requiredstar!!}</legend>

        <div class="field">
            <input type="hidden" name="{{$eachTurnover['turnoverYearField']}}" value="{{$eachTurnover['turnoverYear']}}">
            <input id="{{$eachTurnover['turnoverField']}}" oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 4);" type="text" min="0" max="9999" class="form-control onlynumber fourdigit" name="{{$eachTurnover['turnoverField']}}" placeholder="Turnover for year {{$yr}}" {{$required}} value="{{$eachTurnover['turnover']}} " title="If you have no turnover, enter '0'">

        </div>
        <div class="field">

            <select id="{{$eachTurnover['turnoverUnitField']}}" class="form-select" name="{{$eachTurnover['turnoverUnitField']}}">
                <option value="">-Select-</option>
                {!!$turnover_unit_option!!}
            </select>
        </div>

    </fieldset>
</div>
@endforeach
<h3> Profit After Tax </h3>
@foreach ($profitData as $yr => $eachProfit)
@php
if($year_of_incorporation >= $yr ){
break;
}
$profit_unit_option = GeneralUtils::get_select_option('price_unit',$eachProfit['profitUnit'] ,'',$companyData->authorised_capital_unit);
$required = "";
$requiredstar = "";
if($turnover_base_yr == $yr){
$required = "required";
$requiredstar = '<span class="text-danger">*</span>';
}
@endphp
<div class="col-md-6">
    <fieldset class="scheduler-border pricewithunit">
        <legend class="scheduler-border">Profit After Tax for year {{$yr}} {!!$requiredstar!!}</legend>

        <div class="field">
            <input type="hidden" name="{{$eachProfit['profitYearField']}}" value="{{$eachProfit['profitYear']}}">
            <input
                title="If you have no Profit After Tax, enter '0'."
                id="{{ $eachProfit['profitField'] }}"
                type="text"
                min="-9999"
                max="9999"
                step="any"
                class="form-control onlynumber fourdigit"
                name="{{ $eachProfit['profitField'] }}"
                placeholder="Profit After Tax for year {{ $yr }}"
                {{ $required }}
                value="{{ $eachProfit['profit'] ?? '0' }}"
                oninput="this.value = this.value.replace(/[^-?\d.]/g, '')" />

        </div>
        <div class="field">

            <select id="{{$eachProfit['profitUnitField']}}" class="form-select" name="{{$eachProfit['profitUnitField']}}">
                <option value="">-Select-</option>
                {!!$profit_unit_option!!}
            </select>
        </div>

    </fieldset>
</div>
@endforeach

<h3> BALANCE SHEET for year {{$turnover_base_yr}}</h3>
<div class="col-md-6">
    <fieldset class="scheduler-border pricewithunit">
        <legend class="scheduler-border">Authorized Capital for year {{$turnover_base_yr}} <span class="text-danger">*</span></legend>
        <div class="field">
            <input
                id="authorized_capital"
                type="number"
                min="0"
                max="9999"
                step="1"
                class="form-control onlynumber fourdigit"
                name="authorized_capital"
                placeholder="Authorized Capital for year {{ $turnover_base_yr }}"
                required
                value="{{ old('authorized_capital', optional($companyData)->authorized_capital ?? optional($companyData)->authorised_capital) }}">
        </div>

        <div class="field">

            <select id="authorized_capital_unit" class="form-select" name="authorized_capital_unit">
                <option value="">-Select-</option>
                {!!$authorized_capital_unit_option!!}
            </select>
        </div>
    </fieldset>
</div>



<div class="col-md-6">
    <fieldset class="scheduler-border pricewithunit">
        <legend class="scheduler-border">Paid Up Capital for year {{$turnover_base_yr}} <span class="text-danger">*</span></legend>
        <div class="field">

            <input id="paid_up_capital" type="number" min="0" max="9999" class="form-control onlynumber fourdigit" name="paid_up_capital" placeholder="Paid Up Capital for year {{$turnover_base_yr}}" required value="{{ old('paid_up_capital',optional($companyData)->paid_up_capital ?? optional($companyData)->paidup_capital)}}">
        </div>
        <div class="field">

            <select id="paid_up_capital_unit" class="form-select" name="paid_up_capital_unit">
                <option value="">-Select-</option>
                {!!$paid_up_capital_unit_option!!}
            </select>
        </div>
    </fieldset>
</div>
<div class="col-md-6">
    <fieldset class="scheduler-border pricewithunit">
        <legend class="scheduler-border">Net Worth for year {{$turnover_base_yr}} <span class="text-danger">*</span></legend>
        <div class="field">

            <input title="If you have no Net Worth, enter '0'." id="net_worth" type="text" oninput="this.value = this.value.replace(/[^-?\d.]/g, '')" min="-9999" max="9999" class="form-control onlynumber fourdigit" name="net_worth" placeholder="Net Worth for year {{$turnover_base_yr}}" required value="{{ old('net_worth',isset($companyData)? $companyData->net_worth:'')}}">
        </div>
        <div class="field">

            <select id="net_worth_unit" class="form-select" name="net_worth_unit">
                <option value="">-Select-</option>
                {!!$net_worth_unit_option!!}
            </select>
        </div>
    </fieldset>
</div>
<div class="col-md-6">
    <fieldset class="scheduler-border pricewithunit">
        <legend class="scheduler-border">Reserve for year {{$turnover_base_yr}} <span class="text-danger">*</span></legend>
        <div class="field">

            <input id="reserve" title="If you have no Reserve, enter '0'." oninput="this.value = this.value.replace(/[^-?\d.]/g, '')"
                type="text" min="-9999" max="9999" class="form-control onlynumber fourdigit" name="reserve" placeholder="Reserve for year {{$turnover_base_yr}}" required value="{{ old('reserve',isset($companyData)? $companyData->reserve:'')}}">
        </div>
        <div class="field">

            <select id="reserve_unit" class="form-select" name="reserve_unit">
                <option value="">-Select-</option>
                {!!$reserve_unit_option!!}
            </select>
        </div>
    </fieldset>
</div>
<div class="col-md-6">
    <fieldset class="scheduler-border pricewithunit">
        <legend class="scheduler-border">Secured Creditors for year {{$turnover_base_yr}} <span class="text-danger">*</span></legend>
        <div class="field">

            <input id="secured_creditors" title="If you have no Secured Creditors, enter '0'." type="number" min="0" max="9999" class="form-control onlynumber fourdigit" name="secured_creditors" placeholder="Secured Creditors for year {{$turnover_base_yr}}" required value="{{ old('secured_creditors',isset($companyData)? $companyData->secured_creditors:'')}}">
        </div>
        <div class="field">

            <select id="secured_creditors_unit" class="form-select" name="secured_creditors_unit">
                <option value="">-Select-</option>
                {!!$secured_creditors_unit_option!!}
            </select>
        </div>
    </fieldset>
</div>
<div class="col-md-6">
    <fieldset class="scheduler-border pricewithunit">
        <legend class="scheduler-border">Unsecured Creditors for year {{$turnover_base_yr}} <span class="text-danger">*</span></legend>
        <div class="field">

            <input id="unsecured_creditors" title="If you have no Unsecured Creditors, enter '0'." type="number" min="0" max="9999" class="form-control onlynumber fourdigit" name="unsecured_creditors" placeholder="Unsecured Creditors for year {{$turnover_base_yr}}" required value="{{ old('unsecured_creditors',isset($companyData)? $companyData->unsecured_creditors:'')}}">
        </div>
        <div class="field">

            <select id="unsecured_creditors_unit" class="form-select" name="unsecured_creditors_unit">
                <option value="">-Select-</option>
                {!!$unsecured_creditors_unit_option!!}
            </select>
        </div>
    </fieldset>
</div>
<div class="col-md-6">
    <fieldset class="scheduler-border pricewithunit">
        <legend class="scheduler-border">Land & Building for year {{$turnover_base_yr}} <span class="text-danger">*</span></legend>
        <div class="field">

            <input title="If you have no Land & Building, enter '0'." id="land_building" type="number" min="0" max="9999" class="form-control onlynumber fourdigit" name="land_building" placeholder="Land & Building for year {{$turnover_base_yr}}" required value="{{ old('land_building',isset($companyData)? $companyData->land_building:'')}}">
        </div>
        <div class="field">

            <select id="land_building_unit" class="form-select" name="land_building_unit">
                <option value="">-Select-</option>
                {!!$land_building_unit_option!!}
            </select>
        </div>
    </fieldset>
</div>
<div class="col-md-6">
    <fieldset class="scheduler-border pricewithunit">
        <legend class="scheduler-border">Plant & Machinery for year {{$turnover_base_yr}} <span class="text-danger">*</span></legend>
        <div class="field">

            <input id="plant_machinery" title="If you have no Net Worth, enter '0'." type="number" min="0" max="9999" class="form-control onlynumber fourdigit" name="plant_machinery" placeholder="Plant & Machinery for year {{$turnover_base_yr}}" required value="{{ old('plant_machinery',isset($companyData)? $companyData->plant_machinery:'')}}">
        </div>
        <div class="field">

            <select id="plant_machinery_unit" class="form-select" name="plant_machinery_unit">
                <option value="">-Select-</option>
                {!!$plant_machinery_unit_option!!}
            </select>
        </div>
    </fieldset>
</div>
<div class="col-md-6">
    <fieldset class="scheduler-border pricewithunit">
        <legend class="scheduler-border">Investment for year {{$turnover_base_yr}} <span class="text-danger">*</span></legend>
        <div class="field">

            <input id="investment" title="If you have no Investment, enter '0'." type="number" min="0" max="9999" class="form-control onlynumber fourdigit" name="investment" placeholder="Investment for year {{$turnover_base_yr}}" required value="{{ old('investment',isset($companyData)? $companyData->investment:'')}}">
        </div>
        <div class="field">

            <select id="investment_unit" class="form-select" name="investment_unit">
                <option value="">-Select-</option>
                {!!$investment_unit_option!!}
            </select>
        </div>
    </fieldset>
</div>
<div class="col-md-6">
    <fieldset class="scheduler-border pricewithunit">
        <legend class="scheduler-border">Debtors for year {{$turnover_base_yr}} <span class="text-danger">*</span></legend>
        <div class="field">

            <input id="debtors" title="If you have no Debtors, enter '0'." type="number" min="0" max="9999" class="form-control onlynumber fourdigit" name="debtors" placeholder="Debtors for year {{$turnover_base_yr}}" required value="{{ old('debtors',isset($companyData)? $companyData->debtors:'')}}">
        </div>
        <div class="field">

            <select id="debtors_unit" class="form-select" name="debtors_unit">
                <option value="">-Select-</option>
                {!!$debtors_unit_option!!}
            </select>
        </div>
    </fieldset>
</div>
<div class="col-md-6">
    <fieldset class="scheduler-border pricewithunit">
        <legend class="scheduler-border">Cash & Bank for year {{$turnover_base_yr}} <span class="text-danger">*</span></legend>
        <div class="field">

            <input id="cash_bank" title="If you have no Cash & Bank, enter '0'." type="number" min="0" max="9999" class="form-control onlynumber fourdigit" name="cash_bank" placeholder="Cash & Bank for year {{$turnover_base_yr}}" required value="{{ old('cash_bank',isset($companyData)? $companyData->cash_bank:'')}}">
        </div>
        <div class="field">

            <select id="cash_bank_unit" class="form-select" name="cash_bank_unit">
                <option value="">-Select-</option>
                {!!$cash_bank_unit_option!!}
            </select>
        </div>
    </fieldset>
</div>
@endif