@php
use App\Http\Controllers\Utils\GeneralUtils;
@endphp
@if (in_array($type_of_entity, $listed_company))
@php
    $selected_market_capital_unit = old('market_capitalization_unit',isset($companyData)? $companyData->market_capitalization_unit:'');
    $market_capital_unit_option = GeneralUtils::get_select_option('price_unit',$selected_market_capital_unit);

    $selected_trading_conditions = old('trading_conditions',isset($companyData)? $companyData->trading_conditions:'');
    $trading_conditions_option = GeneralUtils::get_select_option('trading_conditions',$selected_trading_conditions);

    $selected_acquisition_method = old('acquisition_method',isset($companyData)? $companyData->acquisition_method:'');
    $acquisition_method_option = GeneralUtils::get_select_option('acquisition_method',$selected_acquisition_method);

    $selected_promoters_holding = old('promoters_holding',isset($companyData)? $companyData->promoters_holding:'');
    $promoters_holding_option = GeneralUtils::get_percentage_option($selected_promoters_holding);

    $selected_transferable_holding = old('transferable_holding',isset($companyData)? $companyData->transferable_holding:'');
    $transferable_holding_option = GeneralUtils::get_percentage_option($selected_transferable_holding);

    $selected_public_holding = old('public_holding',isset($companyData)? $companyData->public_holding:'');
    $public_holding_option = GeneralUtils::get_percentage_option($selected_public_holding);
@endphp
<div class="col-md-6">
    <div class="field input-group">
        <label for="promoters_holding">Promoters Shareholding <span class="text-danger">*</span></label>
        <select id="promoters_holding" class="form-select" name="promoters_holding" required aria-describedby="promoters_holding">
            <option value="">-Select-</option>
            {!!$promoters_holding_option!!}
        </select><span class="input-group-text" class="promoters_holding">%</span>
    </div>
</div>
<div class="col-md-6">
    <div class="field input-group">
        <label for="transferable_holding">Transferable Shareholding <span class="text-danger">*</span></label>
        <select id="transferable_holding" class="form-select" name="transferable_holding" required aria-describedby="transferable_holding">
            <option value="">-Select-</option>
            {!!$transferable_holding_option!!}
        </select><span class="input-group-text" class="transferable_holding">%</span>
    </div>
</div>

<div class="col-md-6">
    <div class="field input-group">
        <label for="public_holding">Public Shareholding <span class="text-danger">*</span></label>
        <select id="public_holding" class="form-select" name="public_holding" required aria-describedby="public_holding">
            <option value="">-Select-</option>
            {!!$public_holding_option!!}
        </select><span class="input-group-text" class="public_holding">%</span>
    </div>
</div>
<div class="col-md-6">
    <div class="field">
	    <label for="current_market_price">Current Market Price <span class="text-danger">*</span></label>
	  	<input id="current_market_price"  type="number" step="1" min="0" class="form-control onlynumber fourdigit" required name="current_market_price" placeholder="Current Market 
Price"  value="{{ old('current_market_price',isset($companyData)? $companyData->current_market_price:'')}}">
	</div>
</div> 
<div class="col-md-6">
    <div class="field">
	    <label for="high_52_weeks">52 Weeks High <span class="text-danger">*</span></label>
	  	<input id="high_52_weeks"  type="number" step="1" min="0" class="form-control onlynumber fourdigit" name="high_52_weeks" required placeholder="52 Weeks High"  value="{{ old('high_52_weeks',isset($companyData)? $companyData->high_52_weeks:'')}}">
	</div>
</div>
<div class="col-md-6">
    <div class="field">
    	<label for="low_52_weeks">52 Weeks Low <span class="text-danger">*</span></label>
    	<input id="low_52_weeks"  type="number" step="1" min="0" class="form-control onlynumber fourdigit" name="low_52_weeks" required placeholder="52 Weeks Low"  value="{{ old('low_52_weeks',isset($companyData)? $companyData->low_52_weeks:'')}}">
	</div>
</div>

<div class="col-md-6">
	<fieldset class="scheduler-border pricewithunit">
	<legend class="scheduler-border">Market Capitalization <span class="text-danger">*</span></legend>
    <div class="field">
	    
	  	<input id="market_capitalization"  type="number" step="1" min="0" class="form-control onlynumber fourdigit" required name="market_capitalization" placeholder="Market Capitalization"  value="{{ old('market_capitalization',isset($companyData)? $companyData->market_capitalization:'')}}">
	</div>
	<div class="field">
    	
      	<select id="market_capitalization_unit" class="form-select" name="market_capitalization_unit" required >
        <option value="">-Select-</option>
        {!!$market_capital_unit_option!!}
        </select>
    </div>
    </fieldset>
</div>


<div class="col-md-6">
	<div class="field">
            <label for="trading_conditions">Trading Conditions <span class="text-danger">*</span></label>
            <select id="trading_conditions" class="form-select" name="trading_conditions" required >
                <option value="">-Select-</option>
                {!!$trading_conditions_option!!}
            </select>
    </div>
</div>
<div class="col-md-6">
    <div class="field">
            <label for="acquisition_method">Acquisition method <span class="text-danger">*</span></label>
            <select id="acquisition_method" class="form-select" name="acquisition_method" required >
                <option value="">-Select-</option>
                {!!$acquisition_method_option!!}
            </select>
    </div>
</div>
<div class="col-md-6">
    <div class="field">
	    <label for="face_value">Face Value <span class="text-danger">*</span></label>
	  	<input id="face_value"  type="number" step="1" min="0" class="form-control onlynumber " name="face_value" placeholder="Face Value"  value="{{ old('face_value',isset($companyData)? $companyData->face_value:'')}}" required>
	</div>
</div>
	@if (in_array($type_of_entity, $bse_main_board))
	@php
		$selected_bse_main_board_group = old('bse_main_board_group',isset($companyData)? $companyData->bse_main_board_group:'');
	    $bse_main_board_group_option = GeneralUtils::get_select_option('bse_main_board_group',$selected_bse_main_board_group);
	@endphp
<div class="col-md-6">
    <div class="field">
            <label for="bse_main_board_group">Group <span class="text-danger">*</span></label>
            <select id="bse_main_board_group" class="form-select" name="bse_main_board_group" required>
                <option value="">-Select-</option>
                {!!$bse_main_board_group_option!!}
            </select>
    </div>
</div>
    @endif

 @endif
 @if (in_array($type_of_entity, $NBFC_company))
	@php
		$selected_type_of_NBFC = old('type_of_NBFC',isset($companyData)? $companyData->type_of_NBFC:'');
	    $type_of_NBFC_option = GeneralUtils::get_select_option('type_of_NBFC',$selected_type_of_NBFC);

	    $selected_size_of_NBFC = old('size_of_NBFC',isset($companyData)? $companyData->size_of_NBFC:'');
	    $size_of_NBFC_option = GeneralUtils::get_select_option('size_of_NBFC',$selected_size_of_NBFC);
	    
	@endphp
	<div class="col-md-6">
        <div class="field">
            <label for="type_of_NBFC">Type of NBFC</label>
            <select id="type_of_NBFC" class="form-select" name="type_of_NBFC" required>
                <option value="">-Select-</option>
                {!!$type_of_NBFC_option!!}
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="field">
            <label for="size_of_NBFC">Size of NBFC</label>
            <select id="size_of_NBFC" class="form-select" name="size_of_NBFC" required>
                <option value="">-Select-</option>
                {!!$size_of_NBFC_option!!}
            </select>
        </div>
    </div>
    
    @endif
