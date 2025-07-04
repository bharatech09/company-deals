<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
use App\Models\Buyer;
use App\Http\Controllers\Utils\GeneralUtils;
use Illuminate\Support\Facades\Storage;
use App\Rules\CompanyCin;
use App\Rules\GstFormat;
use App\Rules\UniqueCompanyName;
use App\Rules\NoEmailNoMobile;

class CompanyController extends Controller
{
    public function closedeal($id, $buyer_id)
    {
        $company = Company::findOrFail($id);
        $seller = \Auth::guard('user')->user();
        $deal_close_amount = intval($seller->amount_deal_closed) + intval($company->ask_price_amount);
        $deal_close_no = 1 + intval($seller->no_deal_closed);
        $seller->update(['no_deal_closed' => $deal_close_no, 'amount_deal_closed' => $deal_close_amount]);
        $buyer = Buyer::findOrFail($buyer_id);
        $buyer_amount_deal_closed = intval($buyer->buyer_amount_deal_closed) + intval($company->ask_price_amount);
        $buyer_no_deal_closed = 1 + intval($buyer->buyer_no_deal_closed);
        $buyer->update(['buyer_no_deal_closed' => $buyer_no_deal_closed, 'buyer_amount_deal_closed' => $buyer_amount_deal_closed]);
        $company->update(['deal_closed' => 1, "buyer_id" => $buyer_id]);
        return redirect()->route("user.seller.dashboard")->with('status', 'Your company deal is closed successfully.');
    }
    public function download_document($company_id, $field_name)
    {
        $companyData = Company::findOrFail($company_id);
        $path = $companyData->$field_name;

        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->download($path);
        }
        return response()->json(['error' => 'File not found'], 404);
    }
    public function delete_document($company_id, $field_name)
    {
        $companyData = Company::findOrFail($company_id);
        $filePath = $companyData->$field_name;
        Storage::disk('public')->delete($filePath);
        //  unlink($filePath);
        $data = array();
        $data[$field_name] = null;
        $companyData->update($data);
        $message = '<input id="' . $field_name . '"  type="file" class="form-control file_input" name="' . $field_name . '"  value="">';
        return response()->json([
            'message' => $message
        ]);
    }
    public function uploadDocument(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB = 5120KB
        ]);
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $field_name = $request->input('fild_name');
            $company_id = $request->input('company_id');
            $companyData = Company::findOrFail($company_id);

            $originalName = $field_name . time() . "." . $file->getClientOriginalExtension();
            $upload_folder = 'companydocuments/' . $company_id;
            $path = $file->storeAs($upload_folder, $originalName, 'public');
            $data = array();
            $data[$field_name] = $path;
            $companyData->update($data);
            $message = '<p class="alert alert-success">Upload successful!</p>';
            $message .= '<a class="cta-primary" href="' . route('user.seller.companyform.download_document', [$company_id, $field_name]) . '">Download</a> ';
            $message .= '<button type="button" class="cta-primary document_delete" data-url="' . route('user.seller.companyform.delete_document', [$company_id, $field_name]) . '">delete</button> ';
            return response()->json([
                'message' => $message
            ]);
        }
    }
    public function seller_companylist()
    {
        $arrCompany =  Company::seller_companies("inactive");
        return view('pages.user.seller_companylist', compact('arrCompany'));
    }

    public function showstep1(Request $request)
    {
        $companyData = null;
        if ($request->input('id') > 0) {
            $companyData = Company::findOrFail($request->input('id'));
        }
        return view('pages.user.company.form_step1', compact('companyData'));
    }

    public function check_name(Request $request)
    {
        $currentId = $request->input('id');
        $name = $request->input('name');
        $exists = null;
        if ($currentId > 0) {
            $exists = Company::whereRaw('LOWER(name) = ?', [strtolower($name)])->where('id', '!=', $currentId)->exists();
        } else {

            $exists = Company::whereRaw('LOWER(name) = ?', [strtolower($name)])->exists();
        }
        /// var_dump($exists);

        if ($exists) {
            return response()->json(['error' => 1, 'message' => 'Name not available. Choose another.']);
        } else {
            return response()->json(['error' => 0, 'message' => 'Name is available']);
        }
    }
    public function additionalstep1(Request $request)
    {
        $companyData = null;
        if ($request->input('id') > 0) {
            $companyData = Company::findOrFail($request->input('id'));
        }
        $listed_company = config('selectoptions.listed_company');
        $bse_main_board = config('selectoptions.bse_main_board');
        $NBFC_company = config('selectoptions.NBFC_company');
        $type_of_entity = $request->input('type_of_entity');
        return view('pages.user.company.form_additional_step1', compact('type_of_entity', 'listed_company', 'companyData', 'bse_main_board', 'NBFC_company'));
    }
    public function additionalstep2(Request $request)
    {
        $companyData = null;
        if ($request->input('id') > 0) {
            $companyData = Company::findOrFail($request->input('id'));
            if ($request->input('turnover_base_yr') > 0 && $companyData->turnover_year1 != $request->input('turnover_base_yr')) {
                $companyData = $this->resetstep2data($companyData);
            }
        }
        if ($request->input('turnover_base_yr') > 0) {
            $turnover_base_yr = $request->input('turnover_base_yr');
        } elseif (isset($companyData) &&  $companyData->turnover_year1 > 0) {
            $turnover_base_yr = $companyData->turnover_year1;
        } else {
            $turnover_base_yr = "";
        }
        //        echo "-->".session()->getOldInput("turnover1");
        $turnoverData = array();
        $profitData = array();
        if (!empty($turnover_base_yr) && $turnover_base_yr > 0) {
            for ($i = $turnover_base_yr, $j = 1; $i > $turnover_base_yr - 5; $i--, $j++) {
                $turnoverYearKey = 'turnover_year' . $j;
                $turnoverKey = 'turnover' . $j;
                $turnoverUnitKey = 'turnover_unit' . $j;
                $turnoverData[$i] = array(
                    'turnoverYearField' => $turnoverYearKey,
                    'turnoverYear' => $i,
                    'turnoverField' => $turnoverKey,
                    'turnover' => old($turnoverKey, isset($companyData) ? $companyData->$turnoverKey : ''),
                    'turnoverUnitField' => $turnoverUnitKey,
                    'turnoverUnit' => old($turnoverUnitKey, isset($companyData) ? $companyData->$turnoverUnitKey : ''),
                );

                $profitYearKey = 'profit_year' . $j;
                $profitKey = 'profit' . $j;
                $profitUnitKey = 'profit_unit' . $j;
                $profitData[$i] = array(
                    'profitYearField' => $profitYearKey,
                    'profitYear' => $i,
                    'profitField' => $profitKey,
                    'profit' => old($profitKey, isset($companyData) ? $companyData->$profitKey : ''),
                    'profitUnitField' => $profitUnitKey,
                    'profitUnit' => old($profitUnitKey, isset($companyData) ? $companyData->$profitUnitKey : ''),
                );
            }
        }
        return view('pages.user.company.form_additional_step2', compact('companyData', 'turnover_base_yr', 'turnoverData', 'profitData'));
    }
    public function savestep1(Request $request)
    {
        $companyData = null;
        if ($request->input('id') > 0) {
            $companyData = Company::findOrFail($request->input('id'));
        }
        if ($companyData && $companyData->status == 'active') {
            $validatedData = $request->validate([
                'have_gst' => 'required|string',
            ]);
        } else {


            $validatedData = $request->validate([
                'type_of_entity' => 'required|string',
                'name' => ['required', 'string', new UniqueCompanyName(), new NoEmailNoMobile()],
                //    'cin_llpin' => ['required',new CompanyCin()],
                'roc' => 'required|string',
                'year_of_incorporation' => 'required|string',
                'industry' => 'required|string',
                'have_gst' => 'required|string',
                //   'gst' => [new GstFormat()],

            ]);
        }
        //    $validatedData['gst'] = $request->input('gst');

        $validatedData['authorised_capital'] = $request->input('authorised_capital');
        $validatedData['authorised_capital_unit'] = $request->input('authorised_capital_unit');
        $validatedData['authorised_capital_amount'] = GeneralUtils::calculate_actual_ask_price($request->input('authorised_capital'), $request->input('authorised_capital_unit'));

        $validatedData['paidup_capital'] = $request->input('paidup_capital');
        $validatedData['paidup_capital_unit'] = $request->input('paidup_capital_unit');
        $validatedData['paidup_capital_amount'] = GeneralUtils::calculate_actual_ask_price($request->input('paidup_capital'), $request->input('paidup_capital_unit'));
        $validatedData['activity_code'] = $request->input('activity_code');

        $validatedData['name_prefix'] = $request->input('name_prefix');
        $validatedData['current_market_price'] = $request->input('current_market_price');
        $validatedData['high_52_weeks'] = $request->input('high_52_weeks');
        $validatedData['low_52_weeks'] = $request->input('low_52_weeks');
        $validatedData['promoters_holding'] = $request->input('promoters_holding');
        $validatedData['transferable_holding'] = $request->input('transferable_holding');
        $validatedData['public_holding'] = $request->input('public_holding');
        $validatedData['market_capitalization'] = $request->input('market_capitalization');
        $validatedData['market_capitalization_unit'] = $request->input('market_capitalization_unit');
        $validatedData['market_capitalization_amount'] = GeneralUtils::calculate_actual_ask_price($request->input('market_capitalization'), $request->input('market_capitalization_unit'));
        $validatedData['trading_conditions'] = $request->input('trading_conditions');
        $validatedData['acquisition_method'] = $request->input('acquisition_method');
        $validatedData['face_value'] = $request->input('face_value');
        $validatedData['bse_main_board_group'] = $request->input('bse_main_board_group');
        $validatedData['no_of_directors'] = $request->input('no_of_directors');
        $validatedData['no_of_promoters'] = $request->input('no_of_promoters');
        $validatedData['demat_shareholding'] = $request->input('demat_shareholding');
        $validatedData['physical_shareholding'] = $request->input('physical_shareholding');
        $validatedData['type_of_NBFC'] = $request->input('type_of_NBFC');
        $validatedData['size_of_NBFC'] = $request->input('size_of_NBFC');

        if ($request->input('id') > 0) {
            # $companyData = Company::findOrFail($request->input('id'));
            $companyData->update($validatedData);
        } else {
            $validatedData['status'] = 'inactive';
            $validatedData['urn'] = uniqid();
            $validatedData['user_id'] = \Auth::guard('user')->id();
            $companyData = Company::create($validatedData);
        }
        return redirect()->route('user.seller.companyform.showstep2', ['id' => $companyData->id]);

        //  return view('pages.user.company.form_step2', compact('companyData'));
    }
    public function showstep2(Request $request)
    {
        $companyData = Company::findOrFail($request->input('id'));
        if ($companyData->year_of_incorporation == date('Y')) {
             $companyData = $this->resetstep2data($companyData);
            $companyData->update();
            return redirect()->route('user.seller.companyform.showstep3', ['id' => $companyData->id]);
        }


        return view('pages.user.company.form_step2', compact('companyData'));
    }
    public function savestep2(Request $request)
    {

        $companyData = Company::findOrFail($request->input('id'));
        $validatedData['turnover_year1'] = $request->input('turnover_year1');
        $validatedData['turnover1'] = $request->input('turnover1');
        $validatedData['turnover_unit1'] = $request->input('turnover_unit1');
        $validatedData['turnover_amount1'] = GeneralUtils::calculate_actual_ask_price($request->input('turnover1'), $request->input('turnover_unit1'));

        $validatedData['turnover_year2'] = $request->input('turnover_year2');
        $validatedData['turnover2'] = $request->input('turnover2');
        $validatedData['turnover_unit2'] = $request->input('turnover_unit2');
        $validatedData['turnover_amount2'] = GeneralUtils::calculate_actual_ask_price($request->input('turnover2'), $request->input('turnover_unit2'));

        $validatedData['turnover_year3'] = $request->input('turnover_year3');
        $validatedData['turnover3'] = $request->input('turnover3');
        $validatedData['turnover_unit3'] = $request->input('turnover_unit3');
        $validatedData['turnover_amount3'] = GeneralUtils::calculate_actual_ask_price($request->input('turnover3'), $request->input('turnover_unit3'));

        $validatedData['turnover_year4'] = $request->input('turnover_year4');
        $validatedData['turnover4'] = $request->input('turnover4');
        $validatedData['turnover_unit4'] = $request->input('turnover_unit4');
        $validatedData['turnover_amount4'] = GeneralUtils::calculate_actual_ask_price($request->input('turnover4'), $request->input('turnover_unit4'));

        $validatedData['turnover_year5'] = $request->input('turnover_year5');
        $validatedData['turnover5'] = $request->input('turnover5');
        $validatedData['turnover_unit5'] = $request->input('turnover_unit5');
        $validatedData['turnover_amount5'] = GeneralUtils::calculate_actual_ask_price($request->input('turnover5'), $request->input('turnover_unit5'));

        $validatedData['profit_year1'] = $request->input('profit_year1');
        $validatedData['profit1'] = $request->input('profit1');
        $validatedData['profit_unit1'] = $request->input('profit_unit1');
        $validatedData['profit_amount1'] = GeneralUtils::calculate_actual_ask_price($request->input('profit1'), $request->input('profit_unit1'));

        $validatedData['profit_year2'] = $request->input('profit_year2');
        $validatedData['profit2'] = $request->input('profit2');
        $validatedData['profit_unit2'] = $request->input('profit_unit2');
        $validatedData['profit_amount2'] = GeneralUtils::calculate_actual_ask_price($request->input('profit2'), $request->input('profit_unit2'));

        $validatedData['profit_year3'] = $request->input('profit_year3');
        $validatedData['profit3'] = $request->input('profit3');
        $validatedData['profit_unit3'] = $request->input('profit_unit3');
        $validatedData['profit_amount3'] = GeneralUtils::calculate_actual_ask_price($request->input('profit3'), $request->input('profit_unit3'));

        $validatedData['profit_year4'] = $request->input('profit_year4');
        $validatedData['profit4'] = $request->input('profit4');
        $validatedData['profit_unit4'] = $request->input('profit_unit4');
        $validatedData['profit_amount4'] = GeneralUtils::calculate_actual_ask_price($request->input('profit4'), $request->input('profit_unit4'));

        $validatedData['profit_year5'] = $request->input('profit_year5');
        $validatedData['profit5'] = $request->input('profit5');
        $validatedData['profit_unit5'] = $request->input('profit_unit5');
        $validatedData['profit_amount5'] = GeneralUtils::calculate_actual_ask_price($request->input('profit5'), $request->input('profit_unit5'));

        $validatedData['authorized_capital'] = $request->input('authorized_capital');
        $validatedData['authorized_capital_unit'] = $request->input('authorized_capital_unit');
        $validatedData['authorized_capital_amount'] = GeneralUtils::calculate_actual_ask_price($request->input('authorized_capital'), $request->input('authorized_capital_unit'));

        $validatedData['paid_up_capital'] = $request->input('paid_up_capital');
        $validatedData['paid_up_capital_unit'] = $request->input('paid_up_capital_unit');
        $validatedData['paid_up_capital_amount'] = GeneralUtils::calculate_actual_ask_price($request->input('paid_up_capital'), $request->input('paid_up_capital_unit'));

        $validatedData['net_worth'] = $request->input('net_worth');
        $validatedData['net_worth_unit'] = $request->input('net_worth_unit');
        $validatedData['net_worth_amount'] = GeneralUtils::calculate_actual_ask_price($request->input('net_worth'), $request->input('net_worth_unit'));

        $validatedData['reserve'] = $request->input('reserve');
        $validatedData['reserve_unit'] = $request->input('reserve_unit');
        $validatedData['reserve_amount'] = GeneralUtils::calculate_actual_ask_price($request->input('reserve'), $request->input('reserve_unit'));

        $validatedData['secured_creditors'] = $request->input('secured_creditors');
        $validatedData['secured_creditors_unit'] = $request->input('secured_creditors_unit');
        $validatedData['secured_creditors_amount'] = GeneralUtils::calculate_actual_ask_price($request->input('secured_creditors'), $request->input('secured_creditors_unit'));

        $validatedData['unsecured_creditors'] = $request->input('unsecured_creditors');
        $validatedData['unsecured_creditors_unit'] = $request->input('unsecured_creditors_unit');
        $validatedData['unsecured_creditors_amount'] = GeneralUtils::calculate_actual_ask_price($request->input('unsecured_creditors'), $request->input('unsecured_creditors_unit'));

        $validatedData['land_building'] = $request->input('land_building');
        $validatedData['land_building_unit'] = $request->input('land_building_unit');
        $validatedData['land_building_amount'] = GeneralUtils::calculate_actual_ask_price($request->input('land_building'), $request->input('land_building_unit'));

        $validatedData['plant_machinery'] = $request->input('plant_machinery');
        $validatedData['plant_machinery_unit'] = $request->input('plant_machinery_unit');
        $validatedData['plant_machinery_amount'] = GeneralUtils::calculate_actual_ask_price($request->input('plant_machinery'), $request->input('plant_machinery_unit'));

        $validatedData['investment'] = $request->input('investment');
        $validatedData['investment_unit'] = $request->input('investment_unit');
        $validatedData['investment_amount'] = GeneralUtils::calculate_actual_ask_price($request->input('investment'), $request->input('investment_unit'));

        $validatedData['debtors'] = $request->input('debtors');
        $validatedData['debtors_unit'] = $request->input('debtors_unit');
        $validatedData['debtors_amount'] = GeneralUtils::calculate_actual_ask_price($request->input('debtors'), $request->input('debtors_unit'));

        $validatedData['cash_bank'] = $request->input('cash_bank');
        $validatedData['cash_bank_unit'] = $request->input('cash_bank_unit');
        $validatedData['cash_bank_amount'] = GeneralUtils::calculate_actual_ask_price($request->input('cash_bank'), $request->input('cash_bank_unit'));

        $companyData->update($validatedData);
        return redirect()->route('user.seller.companyform.showstep3', ['id' => $companyData->id]);
        //  return view('pages.user.company.form_step3', compact('companyData'));
    }

    public function showstep3(Request $request)
    {
         $companyData = Company::findOrFail($request->input('id'));
        return view('pages.user.company.form_step3', compact('companyData'));
    }
    public function savestep3(Request $request)
    {
        $companyData = Company::findOrFail($request->input('id'));
        $validatedData = array();
        $validatedData['roc_status'] = null;
        $validatedData['roc_year'] = null;
        $validatedData['income_tax_status'] = null;
        $validatedData['income_tax_year'] = null;
        $validatedData['gst_status'] = null;
        $validatedData['gst_year'] = null;
        $validatedData['rbi_status'] = null;
        $validatedData['rbi_year'] = null;
        $validatedData['rbi_status'] = null;
        $validatedData['rbi_year'] = null;
        $validatedData['fema_status'] = null;
        $validatedData['fema_year'] = null;
        $validatedData['sebi_status'] = null;
        $validatedData['sebi_year'] = null;
        $validatedData['stock_exchange_status'] = null;
        $validatedData['stock_exchange_year'] = null;
        $validatedData['roc_status'] = $request->input('roc_status');
        if ($request->input('roc_status') == 'Updated upto') {
            $validatedData['roc_year'] = $request->input('roc_year');
        }
        $validatedData['income_tax_status'] = $request->input('income_tax_status');
        if ($request->input('income_tax_status') == 'Updated upto') {
            $validatedData['income_tax_year'] = $request->input('income_tax_year');
        }
        $validatedData['gst_status'] = $request->input('gst_status');
        if ($request->input('gst_status') == 'Updated upto') {
            $validatedData['gst_year'] = $request->input('gst_year');
        }
        $validatedData['rbi_status'] = $request->input('rbi_status');
        if ($request->input('rbi_status')  == 'Updated upto') {
            $validatedData['rbi_year'] = $request->input('rbi_year');
        }
        $validatedData['fema_status'] = $request->input('fema_status');
        if ($request->input('fema_status')  == 'Updated upto') {
            $validatedData['fema_year'] = $request->input('fema_year');
        }
        $validatedData['sebi_status'] = $request->input('sebi_status');
        if ($request->input('sebi_status') == 'Updated upto') {
            $validatedData['sebi_year'] = $request->input('sebi_year');
        }
        $validatedData['stock_exchange_status'] = $request->input('stock_exchange_status');
        if ($request->input('stock_exchange_status') == 'Updated upto') {
            $validatedData['stock_exchange_year'] = $request->input('stock_exchange_year');
        }
        $validatedData['certicate_status'] = $request->input('certicate_status');
        if ($request->input('certicate_status') == 'Updated upto') {
            $validatedData['certicate_year'] = $request->input('certicate_year');
        }
        $companyData->update($validatedData);
        return redirect()->route('user.seller.companyform.showstep4', ['id' => $companyData->id]);
    }
    public function showstep4(Request $request)
    {
        $companyData = Company::findOrFail($request->input('id'));
        $labelArr = array(
            'certificate_incorporation' => 'Certificate of Incorporation & Other Certificates',
            'pan_card' => 'PAN Card',
            'moa_aoa' => 'MoA & AoA',
            'llp_agreement' => 'LLP Agreement(s)',
            'gst_certificate' => 'GST Certificate',
            'audited_fs1' => 'Audited FS of Base Year',
            'audited_fs2' => 'Audited FS of 2nd Year',
            'audited_fs3' => 'Audited FS of 3rd Year',
            'audited_fs4' => 'Audited FS of 4th Year',
            'audited_fs5' => 'Audited FS of 5th Year',
            'rbi_certificate' => 'RBI Certificate(s)',
            'other_document1' => 'Other Document',
            'other_document2' => 'Other Document',
            'other_document3' => 'Other Document',
            'other_document4' => 'Other Document',
        );
        $documents = array();
        foreach ($labelArr as $field_name => $label) {
            $temp = array(
                'field_name' => $field_name,
                'label' => $label,
            );
            if (!empty($companyData->$field_name)) {
                $temp['uploaded'] = 1;
                $temp['download_link'] = route('user.seller.companyform.download_document', [$companyData->id, $field_name]);
                $temp['delete_link'] = route('user.seller.companyform.delete_document', [$companyData->id, $field_name]);
            } else {
                $temp['uploaded'] = 0;
            }
            $documents[$field_name] = $temp;
        }

        return view('pages.user.company.form_step4', compact('companyData', 'documents'));
    }
    public function savestep4(Request $request)
    {

        $companyData = Company::findOrFail($request->input('id'));

        $validated = $request->validate([
            'ask_price' => ['required', 'numeric', 'max:9999'],
            'ask_price_unit' => 'required',

        ]);
        $validated['ask_price_amount'] = GeneralUtils::calculate_actual_ask_price($request->input('ask_price'), $request->input('ask_price_unit'));
        $companyData->update($validated);
        return redirect()->route('user.seller.companylist')->with('status', 'Company saved successfully.');
    }
    private function resetstep2data($companyData)
    {
        $companyData->turnover_year1 = null;
        $companyData->turnover1 = null;
        $companyData->turnover_unit1 = null;
        $companyData->turnover_amount1 = null;

        $companyData->turnover_year2 = null;
        $companyData->turnover2 = null;
        $companyData->turnover_unit2 = null;
        $companyData->turnover_amount2 = null;

        $companyData->turnover_year3 = null;
        $companyData->turnover3 = null;
        $companyData->turnover_unit3 = null;
        $companyData->turnover_amount3 = null;

        $companyData->turnover_year4 = null;
        $companyData->turnover4 = null;
        $companyData->turnover_unit4 = null;
        $companyData->turnover_amount4 = null;

        $companyData->turnover_year5 = null;
        $companyData->turnover5 = null;
        $companyData->turnover_unit5 = null;
        $companyData->turnover_amount5 = null;

        $companyData->profit_year1 = null;
        $companyData->profit1 = null;
        $companyData->profit_unit1 = null;
        $companyData->profit_amount1 = null;

        $companyData->profit_year2 = null;
        $companyData->profit2 = null;
        $companyData->profit_unit2 = null;
        $companyData->profit_amount2 = null;

        $companyData->profit_year3 = null;
        $companyData->profit3 = null;
        $companyData->profit_unit3 = null;
        $companyData->profit_amount3 = null;

        $companyData->profit_year4 = null;
        $companyData->profit4 = null;
        $companyData->profit_unit4 = null;
        $companyData->profit_amount4 = null;

        $companyData->profit_year5 = null;
        $companyData->profit5 = null;
        $companyData->profit_unit5 = null;
        $companyData->profit_amount5 = null;

        $companyData->authorized_capital = null;
        $companyData->authorized_capital_unit = null;
        $companyData->authorized_capital_amount = null;

        $companyData->paid_up_capital = null;
        $companyData->paid_up_capital_unit = null;
        $companyData->paid_up_capital_amount = null;

        $companyData->net_worth = null;
        $companyData->net_worth_unit = null;
        $companyData->net_worth_amount = null;

        $companyData->reserve = null;
        $companyData->reserve_unit = null;
        $companyData->reserve_amount = null;

        $companyData->secured_creditors = null;
        $companyData->secured_creditors_unit = null;
        $companyData->secured_creditors_amount = null;

        $companyData->unsecured_creditors = null;
        $companyData->unsecured_creditors_unit = null;
        $companyData->unsecured_creditors_amount = null;

        $companyData->land_building = null;
        $companyData->land_building_unit = null;
        $companyData->land_building_amount = null;

        $companyData->plant_machinery = null;
        $companyData->plant_machinery_unit = null;
        $companyData->plant_machinery_amount = null;

        $companyData->investment = null;
        $companyData->investment_unit = null;
        $companyData->investment_amount = null;

        $companyData->debtors = null;
        $companyData->debtors_unit = null;
        $companyData->debtors_amount = null;

        $companyData->cash_bank = null;
        $companyData->cash_bank_unit = null;
        $companyData->cash_bank_amount = null;

        return $companyData;
    }
}
