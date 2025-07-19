<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Buyer;
use Kyslik\ColumnSortable\Sortable;

class Company extends Model
{
    use Sortable;

    public $sortable = [
        'urn',
        'name',
        'ask_price',
        'status',
        'deal_closed',
        'home_featured',
        'created_at',
    ];

    protected $fillable = [
        'urn',
        'user_id',
        'type_of_entity',
        'name',
        'name_prefix',
        'cin_llpin',
        'roc',
        'year_of_incorporation',
        'industry',
        'have_gst',
        'gst',
        'current_market_price',
        'high_52_weeks',
        'low_52_weeks',
        'promoters_holding',
        'transferable_holding',
        'public_holding',
        'market_capitalization',
        'market_capitalization_unit',
        'market_capitalization_amount',
        'trading_conditions',
        'acquisition_method',
        'face_value',
        'bse_main_board_group',
        'no_of_directors',
        'no_of_promoters',
        'demat_shareholding',
        'physical_shareholding',
        'authorised_capital',
        'authorised_capital_unit',
        'authorised_capital_amount',
        'paidup_capital',
        'paidup_capital_unit',
        'paidup_capital_amount',
        'activity_code',
        'type_of_NBFC',
        'size_of_NBFC',
        'authorized_capital',
        'income_tax',
        'certificate_incorporation',
        'pan_card',
        'moa_aoa',
        'llp_agreement',
        'gst_certificate',
        'audited_fs1',
        'audited_fs2',
        'audited_fs3',
        'audited_fs4',
        'audited_fs5',
        'rbi_certificate',
        'other_document1',
        'other_document2',
        'other_document3',
        'other_document4',

        'turnover_year1',
        'turnover1',
        'turnover_unit1',
        'turnover_amount1',

        'turnover_year2',
        'turnover2',
        'turnover_unit2',
        'turnover_amount2',

        'turnover_year3',
        'turnover3',
        'turnover_unit3',
        'turnover_amount3',

        'turnover_year4',
        'turnover4',
        'turnover_unit4',
        'turnover_amount4',

        'turnover_year5',
        'turnover5',
        'turnover_unit5',
        'turnover_amount5',

        'profit_year1',
        'profit1',
        'profit_unit1',
        'profit_amount1',

        'profit_year2',
        'profit2',
        'profit_unit2',
        'profit_amount2',

        'profit_year3',
        'profit3',
        'profit_unit3',
        'profit_amount3',

        'profit_year4',
        'profit4',
        'profit_unit4',
        'profit_amount4',

        'profit_year5',
        'profit5',
        'profit_unit5',
        'profit_amount5',

        'authorized_capital',
        'authorized_capital_unit',
        'authorized_capital_amount',

        'paid_up_capital',
        'paid_up_capital_unit',
        'paid_up_capital_amount',

        'net_worth',
        'net_worth_unit',
        'net_worth_amount',

        'reserve',
        'reserve_unit',
        'reserve_amount',

        'secured_creditors',
        'secured_creditors_unit',
        'secured_creditors_amount',

        'unsecured_creditors',
        'unsecured_creditors_unit',
        'unsecured_creditors_amount',

        'land_building',
        'land_building_unit',
        'land_building_amount',

        'plant_machinery',
        'plant_machinery_unit',
        'plant_machinery_amount',

        'investment',
        'investment_unit',
        'investment_amount',

        'debtors',
        'debtors_unit',
        'debtors_amount',

        'cash_bank',
        'cash_bank_unit',
        'cash_bank_amount',

        'roc_status',
        'roc_year',
        'income_tax_status',
        'income_tax_year',
        'gst_status',
        'gst_year',
        'rbi_status',
        'rbi_year',
        'fema_status',
        'fema_year',
        'sebi_status',
        'sebi_year',
        'stock_exchange_status',
        'stock_exchange_year',

        'certicate_status',
        'certicate_year',

        'ask_price',
        'ask_price_unit',
        'ask_price_amount',


        'status',
        'payment_id',
        'deal_closed',
        'buyer_id',
        'home_featured'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function buyers()
    {
        return $this->belongsToMany(Buyer::class)->withPivot('id', 'is_active');
    }
    public function allPayment()
    {
        $payments = self::join('payments', 'companies.id', '=', 'payments.service_id')->where('service_id', '=', $this->id)->where('service_type', '=', 'seller_company')->select('payments.*')->orderBy('created_at', 'desc')->get();
        return $payments;
    }
    public static function seller_companies($condition, $third = false, $fourth = false)
    {
        $user = \Auth::guard('user')->user();
        if ($condition == "all") {
            $companies = $user->companies;
        } else {
            $companies = $user->companies()->where('companies.status', $condition)->get();
        }

        if ($third == true) {
            $companies = $user->companies()->where('companies.status', $condition)->where('companies.deal_closed', 1)->get();
        }
        if ($fourth == true) {
            $companies = $user->companies()->where('companies.status', $condition)->where('companies.deal_closed', 0)->get();
        }

        $arrCompany = array();
        foreach ($companies as $eachCompany) {
            $tempArr = array(
                'id' => $eachCompany->id,
                'urn' => $eachCompany->urn,
                'type_of_entity' => $eachCompany->type_of_entity,
                'name' => $eachCompany->name,
                'name_prefix' => $eachCompany->name_prefix,
                'cin_llpin' => $eachCompany->cin_llpin,
                'roc' => $eachCompany->roc,
                'year_of_incorporation' => $eachCompany->year_of_incorporation,
                'industry' => $eachCompany->industry,
                'status' => ($eachCompany->deal_closed) ? "Deal Closed" : $eachCompany->status,
                'ask_price' => $eachCompany->ask_price,
                'ask_price_unit' => $eachCompany->ask_price_unit,
                'ask_price_amount' => $eachCompany->ask_price_amount,
                'deal_closed' => $eachCompany->deal_closed,
                'buyer_id' => $eachCompany->buyer_id,
                'home_featured' => $eachCompany->home_featured,

                // Financial
                'have_gst' => $eachCompany->have_gst,
                'gst' => $eachCompany->gst,
                'current_market_price' => $eachCompany->current_market_price,
                'high_52_weeks' => $eachCompany->high_52_weeks,
                'low_52_weeks' => $eachCompany->low_52_weeks,
                'promoters_holding' => $eachCompany->promoters_holding,
                'transferable_holding' => $eachCompany->transferable_holding,
                'public_holding' => $eachCompany->public_holding,
                'market_capitalization' => $eachCompany->market_capitalization,
                'market_capitalization_unit' => $eachCompany->market_capitalization_unit,
                'market_capitalization_amount' => $eachCompany->market_capitalization_amount,
                'trading_conditions' => $eachCompany->trading_conditions,
                'acquisition_method' => $eachCompany->acquisition_method,
                'face_value' => $eachCompany->face_value,

                // Structure
                'bse_main_board_group' => $eachCompany->bse_main_board_group,
                'no_of_directors' => $eachCompany->no_of_directors,
                'no_of_promoters' => $eachCompany->no_of_promoters,
                'demat_shareholding' => $eachCompany->demat_shareholding,
                'physical_shareholding' => $eachCompany->physical_shareholding,
                'authorised_capital' => $eachCompany->authorised_capital,
                'authorised_capital_amount' => $eachCompany->authorised_capital_amount,
                'paidup_capital' => $eachCompany->paidup_capital,
                'paidup_capital_amount' => $eachCompany->paidup_capital_amount,
                'authorized_capital' => $eachCompany->authorized_capital,
                'authorized_capital_unit' => $eachCompany->authorized_capital_unit,
                'authorized_capital_amount' => $eachCompany->authorized_capital_amount,
                'paid_up_capital' => $eachCompany->paid_up_capital,
                'paid_up_capital_unit' => $eachCompany->paid_up_capital_unit,
                'paid_up_capital_amount' => $eachCompany->paid_up_capital_amount,

                // NBFC
                'activity_code' => $eachCompany->activity_code,
                'type_of_NBFC' => $eachCompany->type_of_NBFC,
                'size_of_NBFC' => $eachCompany->size_of_NBFC,

                // Balance Sheet
                'net_worth' => $eachCompany->net_worth,
                'net_worth_unit' => $eachCompany->net_worth_unit,
                'net_worth_amount' => $eachCompany->net_worth_amount,
                'reserve' => $eachCompany->reserve,
                'reserve_unit' => $eachCompany->reserve_unit,
                'reserve_amount' => $eachCompany->reserve_amount,
                'secured_creditors' => $eachCompany->secured_creditors,
                'secured_creditors_unit' => $eachCompany->secured_creditors_unit,
                'secured_creditors_amount' => $eachCompany->secured_creditors_amount,
                'unsecured_creditors' => $eachCompany->unsecured_creditors,
                'unsecured_creditors_unit' => $eachCompany->unsecured_creditors_unit,
                'unsecured_creditors_amount' => $eachCompany->unsecured_creditors_amount,
                'land_building' => $eachCompany->land_building,
                'land_building_unit' => $eachCompany->land_building_unit,
                'land_building_amount' => $eachCompany->land_building_amount,
                'plant_machinery' => $eachCompany->plant_machinery,
                'plant_machinery_unit' => $eachCompany->plant_machinery_unit,
                'plant_machinery_amount' => $eachCompany->plant_machinery_amount,
                'investment' => $eachCompany->investment,
                'investment_unit' => $eachCompany->investment_unit,
                'investment_amount' => $eachCompany->investment_amount,
                'debtors' => $eachCompany->debtors,
                'debtors_unit' => $eachCompany->debtors_unit,
                'debtors_amount' => $eachCompany->debtors_amount,
                'cash_bank' => $eachCompany->cash_bank,
                'cash_bank_unit' => $eachCompany->cash_bank_unit,
                'cash_bank_amount' => $eachCompany->cash_bank_amount,

                // Turnover (1 to 5)
                'turnover_year1' => $eachCompany->turnover_year1,
                'turnover1' => $eachCompany->turnover1,
                'turnover_unit1' => $eachCompany->turnover_unit1,
                'turnover_amount1' => $eachCompany->turnover_amount1,
                'turnover_year2' => $eachCompany->turnover_year2,
                'turnover2' => $eachCompany->turnover2,
                'turnover_unit2' => $eachCompany->turnover_unit2,
                'turnover_amount2' => $eachCompany->turnover_amount2,
                'turnover_year3' => $eachCompany->turnover_year3,
                'turnover3' => $eachCompany->turnover3,
                'turnover_unit3' => $eachCompany->turnover_unit3,
                'turnover_amount3' => $eachCompany->turnover_amount3,
                'turnover_year4' => $eachCompany->turnover_year4,
                'turnover4' => $eachCompany->turnover4,
                'turnover_unit4' => $eachCompany->turnover_unit4,
                'turnover_amount4' => $eachCompany->turnover_amount4,
                'turnover_year5' => $eachCompany->turnover_year5,
                'turnover5' => $eachCompany->turnover5,
                'turnover_unit5' => $eachCompany->turnover_unit5,
                'turnover_amount5' => $eachCompany->turnover_amount5,

                // Profit (1 to 5)
                'profit_year1' => $eachCompany->profit_year1,
                'profit1' => $eachCompany->profit1,
                'profit_unit1' => $eachCompany->profit_unit1,
                'profit_amount1' => $eachCompany->profit_amount1,
                'profit_year2' => $eachCompany->profit_year2,
                'profit2' => $eachCompany->profit2,
                'profit_unit2' => $eachCompany->profit_unit2,
                'profit_amount2' => $eachCompany->profit_amount2,
                'profit_year3' => $eachCompany->profit_year3,
                'profit3' => $eachCompany->profit3,
                'profit_unit3' => $eachCompany->profit_unit3,
                'profit_amount3' => $eachCompany->profit_amount3,
                'profit_year4' => $eachCompany->profit_year4,
                'profit4' => $eachCompany->profit4,
                'profit_unit4' => $eachCompany->profit_unit4,
                'profit_amount4' => $eachCompany->profit_amount4,
                'profit_year5' => $eachCompany->profit_year5,
                'profit5' => $eachCompany->profit5,
                'profit_unit5' => $eachCompany->profit_unit5,
                'profit_amount5' => $eachCompany->profit_amount5,

                // Compliance
                'roc_status' => $eachCompany->roc_status,
                'roc_year' => $eachCompany->roc_year,
                'income_tax_status' => $eachCompany->income_tax_status,
                'income_tax_year' => $eachCompany->income_tax_year,
                'gst_status' => $eachCompany->gst_status,
                'gst_year' => $eachCompany->gst_year,
                'rbi_status' => $eachCompany->rbi_status,
                'rbi_year' => $eachCompany->rbi_year,
                'fema_status' => $eachCompany->fema_status,
                'fema_year' => $eachCompany->fema_year,
                'sebi_status' => $eachCompany->sebi_status,
                'sebi_year' => $eachCompany->sebi_year,
                'stock_exchange_status' => $eachCompany->stock_exchange_status,
                'stock_exchange_year' => $eachCompany->stock_exchange_year,
                'certicate_status' => $eachCompany->certicate_status,
                'certicate_year' => $eachCompany->certicate_year,

                // Documents
                'certificate_incorporation' => $eachCompany->certificate_incorporation,
                'pan_card' => $eachCompany->pan_card,
                'moa_aoa' => $eachCompany->moa_aoa,
                'llp_agreement' => $eachCompany->llp_agreement,
                'gst_certificate' => $eachCompany->gst_certificate,
                'audited_fs1' => $eachCompany->audited_fs1,
                'audited_fs2' => $eachCompany->audited_fs2,
                'audited_fs3' => $eachCompany->audited_fs3,
                'audited_fs4' => $eachCompany->audited_fs4,
                'audited_fs5' => $eachCompany->audited_fs5,
                'rbi_certificate' => $eachCompany->rbi_certificate,
                'other_document1' => $eachCompany->other_document1,
                'other_document2' => $eachCompany->other_document2,
                'other_document3' => $eachCompany->other_document3,
                'other_document4' => $eachCompany->other_document4,
            );

            if ($eachCompany->deal_closed == 1 && $eachCompany->buyer_id > 0) {
                $finalBuyer = Buyer::findOrFail($eachCompany->buyer_id);
                $tempArr['finalBuyer'] = "Name: " . $finalBuyer->name . ", Whatsapp: " . $finalBuyer->phone . ", Email: " . $finalBuyer->email . ",<br> Previous deals done: " . $finalBuyer->buyer_no_deal_closed . ", Amount of deals closed: " . number_format(($finalBuyer->buyer_amount_deal_closed) / 1000) . " Thousands";
            }
            $buyers = $eachCompany->buyers;
            $tempArr['no_interested_buyer'] = 0;
            if (count($buyers) > 0) {
                $tempArr['no_interested_buyer'] = count($buyers);
            }
            $buyersArr = array();
            foreach ($buyers as $eachBuyer) {
                // var_dump($eachBuyer);
                if (!is_null($eachBuyer->pivot) && $eachBuyer->pivot->is_active == 'active') {
                    $tempBuyer = array();
                    $tempBuyer['buyerDetail'] = "Name: " . $eachBuyer->name . ", Whatsapp: " . $eachBuyer->phone . ", Email: " . $eachBuyer->email . ",<br> Previous deals done: " . $eachBuyer->buyer_no_deal_closed . ", Amount of deals closed: " . number_format(($eachBuyer->buyer_amount_deal_closed) / 1000) . " Thousands";
                    $tempBuyer['buyer_id'] = $eachBuyer->pivot->buyer_id;
                    $buyersArr[] = $tempBuyer;
                }
            }
            $tempArr['buyers'] = $buyersArr;
            $arrCompany[] = $tempArr;
        }
        return $arrCompany;
    }
}
