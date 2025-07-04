<?php

namespace App\Http\Controllers;

use App\Models\NocTrademark;
use App\Models\Property;
use App\Models\Company;
use App\Models\Assignment;
use App\Models\Buyer;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class BuyerController extends Controller
{

    public function assignment_filter_ajax(Request $request)
    {
        $buyer_id =  \Auth::guard('user')->id();
        $buyer = Buyer::findOrFail($buyer_id);
        $interestedAssignment = $buyer->assignments;

        $interestedAssignmentArr = array();
        foreach ($interestedAssignment as $key => $eachAssignment) {
            $interestedAssignmentArr[] = $eachAssignment->id;
        }
        $filterCon = array();
        $filterCon[] = array('is_active', '=', 'active');
        if (!empty($request->input('category'))) {
            $filterCon[] = array('category', '=', $request->input('category'));
        }


        $assignments = Assignment::where($filterCon)->orderBy('updated_at', 'desc')->get();
        $assignmentArr = array();
        foreach ($assignments as $key => $assignment) {
            if (in_array($assignment->id, $interestedAssignmentArr)) {
                continue;
            }
            $assignmentArr[] = $this->getAssignmentDetail($assignment);
        }
        return view('pages.user.buyer_filter_assignment_ajax', compact('assignmentArr'));
    }
    public function assignment_filter()
    {
        return view('pages.user.buyer_filter_assignment');
    }
    public function assignment_remove_from_interested($id)
    {
        $buyer_id =  \Auth::guard('user')->id();

        if ($buyer_id > 0) {
            $buyer = Buyer::findOrFail($buyer_id);
            if ($buyer) {
                $buyer->assignments()->detach(array($id));
                return redirect()->route("user.buyer.dashboard")->with('status', 'The assignment has been removed from your interested list.');
            }
        }
        return redirect()->route("user.buyer.dashboard")->with('status', 'There is some issue in adding please retry it.');
    }
    public function assignment_addtointerested($id)
    {
        $buyer_id =  \Auth::guard('user')->id();

        if ($buyer_id > 0) {
            $buyer = Buyer::findOrFail($buyer_id);
            if ($buyer) {
                $buyer->assignments()->syncWithoutDetaching(array($id));
                return redirect()->route("user.buyer.dashboard")->with('status', 'The assignment has been added to your interested list.');
            }
        }
        return redirect()->route("user.buyer.dashboard")->with('status', 'There is some issue in adding please retry it.');
    }
    public function company_remove_from_interested($id)
    {
        $buyer_id =  \Auth::guard('user')->id();

        if ($buyer_id > 0) {
            $buyer = Buyer::findOrFail($buyer_id);
            if ($buyer) {
                $buyer->companies()->detach(array($id));
                return redirect()->route("user.buyer.dashboard")->with('status', 'The company has been removed from your interested list.');
            }
        }
        return redirect()->route("user.buyer.dashboard")->with('status', 'There is some issue in adding please retry it.');
    }
    public function company_addtointerested($id)
    {
        $buyer_id =  \Auth::guard('user')->id();

        if ($buyer_id > 0) {
            $buyer = Buyer::findOrFail($buyer_id);
            if ($buyer) {
                $buyer->companies()->syncWithoutDetaching(array($id));
                return redirect()->route("user.buyer.dashboard")->with('status', 'The company has been added to your interested list.');
            }
        }
        return redirect()->route("user.buyer.dashboard")->with('status', 'There is some issue in adding please retry it.');
    }
    public function property_remove_from_interested($id)
    {
        $buyer_id =  \Auth::guard('user')->id();

        if ($buyer_id > 0) {
            $buyer = Buyer::findOrFail($buyer_id);
            if ($buyer) {
                $buyer->properties()->detach(array($id));
                return redirect()->route("user.buyer.dashboard")->with('status', 'The property has been removed from your interested list.');
            }
        }
        return redirect()->route("user.buyer.dashboard")->with('status', 'There is some issue in adding please retry it.');
    }


    public function trademark_addtointerested($id)
    {
        $buyer_id =  \Auth::guard('user')->id();

        if ($buyer_id > 0) {
            $buyer = Buyer::findOrFail($buyer_id);
            if ($buyer) {
                $buyer->noctrademarks()->syncWithoutDetaching(array($id));
                return redirect()->route("user.buyer.dashboard")->with('status', 'The NOC of trademark has been added to your interested list.');
            }
        }
        return redirect()->route("user.buyer.dashboard")->with('status', 'There is some issue in adding please retry it.');
    }
    public function trademark_remove_from_interested($id)
    {
        $buyer_id =  \Auth::guard('user')->id();

        if ($buyer_id > 0) {
            $buyer = Buyer::findOrFail($buyer_id);
            if ($buyer) {
                $buyer->noctrademarks()->detach(array($id));
                return redirect()->route("user.buyer.dashboard")->with('status', 'The NOC of trademark has been removed from your interested list.');
            }
        }
        return redirect()->route("user.buyer.dashboard")->with('status', 'There is some issue in adding please retry it.');
    }


    public function property_addtointerested($id)
    {
        $buyer_id =  \Auth::guard('user')->id();

        if ($buyer_id > 0) {
            $buyer = Buyer::findOrFail($buyer_id);
            if ($buyer) {
                $buyer->properties()->syncWithoutDetaching(array($id));
                return redirect()->route("user.buyer.dashboard")->with('status', 'The property has been added to your interested list.');
            }
        }
        return redirect()->route("user.buyer.dashboard")->with('status', 'There is some issue in adding please retry it.');
    }
    public function dashboard()
    {

        $buyer_id = \Auth::guard('user')->id();
        $buyer = Buyer::findOrFail($buyer_id);

        $interestedCompany = $buyer->companies()->where(function ($q) {
                $q->where('companies.deal_closed', 0)
                    ->orWhereNull(column: 'companies.deal_closed');   // 0 या NULL दोनों ऐक्सेप्ट
            })->orderBy('updated_at', 'desc')->get();
        $interestedCompanyArr = [];

        foreach ($interestedCompany as $eachCompany) {
            $tempCompany = $this->getCompanyDetail($eachCompany);
            $tempCompany['buyer_status'] = $eachCompany->pivot->is_active;

            if ($eachCompany->pivot->is_active === 'active') {
                $seller = User::find($eachCompany->user_id);
                if ($seller) {
                    $tempCompany['seller'] = "Name: {$seller->name}, WhatsApp No: {$seller->phone},<br> Email: {$seller->email}";
                    if ($seller->amount_deal_closed > 0) {
                        $tempCompany['seller'] .= "<br>No of deal closed: {$seller->no_deal_closed}, amount of deal closed: " . number_format($seller->amount_deal_closed / 1000, 2) . " Thousands";
                    }
                }
            }

            // Include only fields present in the JSON
            $tempCompany['type_of_entity'] = $eachCompany->type_of_entity;
            $tempCompany['name'] = $eachCompany->name;
            $tempCompany['name_prefix'] = $eachCompany->name_prefix;
            $tempCompany['roc'] = $eachCompany->roc;
            $tempCompany['year_of_incorporation'] = $eachCompany->year_of_incorporation;
            $tempCompany['industry'] = $eachCompany->industry;

            $tempCompany['have_gst'] = $eachCompany->have_gst;
            $tempCompany['current_market_price'] = $eachCompany->current_market_price;
            $tempCompany['high_52_weeks'] = $eachCompany->high_52_weeks;
            $tempCompany['low_52_weeks'] = $eachCompany->low_52_weeks;

            $tempCompany['promoters_holding'] = $eachCompany->promoters_holding;
            $tempCompany['transferable_holding'] = $eachCompany->transferable_holding;
            $tempCompany['public_holding'] = $eachCompany->public_holding;
            $tempCompany['market_capitalization'] = $eachCompany->market_capitalization;
            $tempCompany['trading_conditions'] = $eachCompany->trading_conditions;
            $tempCompany['acquisition_method'] = $eachCompany->acquisition_method;
            $tempCompany['face_value'] = $eachCompany->face_value;

            $tempCompany['no_of_directors'] = $eachCompany->no_of_directors;
            $tempCompany['no_of_promoters'] = $eachCompany->no_of_promoters;
            $tempCompany['demat_shareholding'] = $eachCompany->demat_shareholding;
            $tempCompany['physical_shareholding'] = $eachCompany->physical_shareholding;

            $tempCompany['authorised_capital'] = $eachCompany->authorised_capital;
            $tempCompany['authorised_capital_amount'] = $eachCompany->authorised_capital_amount;
            $tempCompany['paidup_capital'] = $eachCompany->paidup_capital;
            $tempCompany['paidup_capital_amount'] = $eachCompany->paidup_capital_amount;
            $tempCompany['activity_code'] = $eachCompany->activity_code;
            $tempCompany['type_of_NBFC'] = $eachCompany->type_of_NBFC;
            $tempCompany['size_of_NBFC'] = $eachCompany->size_of_NBFC;
            $tempCompany['ask_price_amount'] = $eachCompany->ask_price_amount;
            $tempCompany['market_capitalization_amount'] = $eachCompany->market_capitalization_amount;
            $tempCompany['market_capitalization_unit'] = $eachCompany->market_capitalization_unit;

            // Turnover
            for ($i = 1; $i <= 5; $i++) {
                $tempCompany["turnover_year{$i}"] = $eachCompany->{"turnover_year{$i}"};
                $tempCompany["turnover{$i}"] = $eachCompany->{"turnover{$i}"};
                $tempCompany["turnover_amount{$i}"] = $eachCompany->{"turnover_amount{$i}"};
                $tempCompany["turnover_unit{$i}"] = $eachCompany->{"turnover_unit{$i}"};
            }

            // Profit After Tax
            for ($i = 1; $i <= 5; $i++) {
                $tempCompany["profit_year{$i}"] = $eachCompany->{"profit_year{$i}"};
                $tempCompany["profit{$i}"] = $eachCompany->{"profit{$i}"};
                $tempCompany["profit_amount{$i}"] = $eachCompany->{"profit_amount{$i}"};
                $tempCompany["profit_unit{$i}"] = $eachCompany->{"profit_unit{$i}"};
            }

            // Balance Sheet
            $tempCompany['net_worth'] = $eachCompany->net_worth;
            $tempCompany['net_worth_amount'] = $eachCompany->net_worth_amount;
            $tempCompany['net_worth_unit'] = $eachCompany->net_worth_unit;
            $tempCompany['reserve_amount'] = $eachCompany->reserve_amount;
            $tempCompany['reserve_unit'] = $eachCompany->reserve_unit;
            $tempCompany['reserve'] = $eachCompany->reserve;
            $tempCompany['secured_creditors_amount'] = $eachCompany->secured_creditors_amount;
            $tempCompany['secured_creditors_unit'] = $eachCompany->secured_creditors_unit;
            $tempCompany['unsecured_creditors_amount'] = $eachCompany->unsecured_creditors_amount;
            $tempCompany['unsecured_creditors_unit'] = $eachCompany->unsecured_creditors_unit;
            $tempCompany['land_building_amount'] = $eachCompany->land_building_amount;
            $tempCompany['land_building_unit'] = $eachCompany->land_building_unit;
            $tempCompany['plant_machinery_amount'] = $eachCompany->plant_machinery_amount;
            $tempCompany['plant_machinery_unit'] = $eachCompany->plant_machinery_unit;
            $tempCompany['investment_amount'] = $eachCompany->investment_amount;
            $tempCompany['plant_machinery'] = $eachCompany->plant_machinery;
            $tempCompany['investment_unit'] = $eachCompany->investment_unit;
            $tempCompany['debtors_amount'] = $eachCompany->debtors_amount;
            $tempCompany['debtors_unit'] = $eachCompany->debtors_unit;
            $tempCompany['cash_bank_amount'] = $eachCompany->cash_bank_amount;
            $tempCompany['cash_bank_unit'] = $eachCompany->cash_bank_unit;

            // Compliance
            $tempCompany['roc_status'] = $eachCompany->roc_status;
            $tempCompany['roc_year'] = $eachCompany->roc_year;
            $tempCompany['income_tax_status'] = $eachCompany->income_tax_status;
            $tempCompany['income_tax_year'] = $eachCompany->income_tax_year;
            $tempCompany['gst_status'] = $eachCompany->gst_status;
            $tempCompany['gst_year'] = $eachCompany->gst_year;
            $tempCompany['rbi_status'] = $eachCompany->rbi_status;
            $tempCompany['rbi_year'] = $eachCompany->rbi_year;
            $tempCompany['fema_status'] = $eachCompany->fema_status;
            $tempCompany['fema_year'] = $eachCompany->fema_year;
            $tempCompany['sebi_status'] = $eachCompany->sebi_status;
            $tempCompany['sebi_year'] = $eachCompany->sebi_year;
            $tempCompany['stock_exchange_status'] = $eachCompany->stock_exchange_status;
            $tempCompany['stock_exchange_year'] = $eachCompany->stock_exchange_year;

            $interestedCompanyArr[] = $tempCompany;
        }

        $interestedProperty = $buyer->properties()->where(function ($q) {
                $q->where('properties.deal_closed', 0)
                    ->orWhereNull(column: 'properties.deal_closed');   // 0 या NULL दोनों ऐक्सेप्ट
            })->orderBy('updated_at', 'desc')->get();
        $interestedPropertyArr = array();
        foreach ($interestedProperty as $key => $eachProperty) {

            $tempProperty = $this->getPropertyDetail($eachProperty);
            $tempProperty['buyer_status'] = $eachProperty->pivot->is_active;
            if ($eachProperty->pivot->is_active == 'active') {
                $seller_id = $eachProperty->user_id;
                $seller = User::where('id', $seller_id)->first();
                $tempProperty['seller'] = "Name: " . $seller->name . ", WhatsApp No: " . $seller->phone . ", <br>Email: " . $seller->email;
                if ($seller->amount_deal_closed > 0) {
                    $tempProperty['seller'] .= "<br>No of deal closed: " . $seller->no_deal_closed . ", amount of deal closed: " . number_format($seller->amount_deal_closed / 1000.0, 2) . " Thousands";
                }
            }
            $interestedPropertyArr[] = $tempProperty;
        }

        $interestedTrademarks = $buyer->noctrademarks()->where(function ($q) {
                $q->where('noc_trademarks.deal_closed', 0)
                    ->orWhereNull(column: 'noc_trademarks.deal_closed');   // 0 या NULL दोनों ऐक्सेप्ट
            })->orderBy('updated_at', 'desc')->get();
        $interestedTrademarkArr = array();
        foreach ($interestedTrademarks as $key => $trademark) {
            //   if($trademark->deal_closed ==1)
            //      continue;
            $tempTrademark = $this->getTrademarkDetail($trademark);
            $tempTrademark['buyer_status'] = $trademark->pivot->is_active;
            if ($trademark->pivot->is_active == 'active') {
                $seller_id = $trademark->user_id;
                $seller = User::where('id', $seller_id)->first();
                //  var_dump($seller);
                $tempTrademark['seller'] = "Name: " . $seller->name . ", WhatsApp No: " . $seller->phone . ",<br> Email: " . $seller->email;
                if ($seller->amount_deal_closed > 0) {
                    $tempTrademark['seller'] .= "<br>No of deal closed: " . $seller->no_deal_closed . ", amount of deal closed: " . number_format($seller->amount_deal_closed / 1000.0, 2) . " Thousands";
                }
            }
            $interestedTrademarkArr[] = $tempTrademark;
        }

        $interestedAssignments = $buyer->assignments()->where(function ($q) {
                $q->where('assignments.deal_closed', 0)
                    ->orWhereNull(column: 'assignments.deal_closed');   // 0 या NULL दोनों ऐक्सेप्ट
            })->orderBy('updated_at', 'desc')->get();
        $interestedAssignmentArr = array();
        foreach ($interestedAssignments as $key => $assignment) {
            //   if($assignment->deal_closed ==1)
            //      continue;
            $tempAssignment = $this->getAssignmentDetail($assignment);
            $tempAssignment['buyer_status'] = $assignment->pivot->is_active;
            if ($assignment->pivot->is_active == 'active') {
                $seller_id = $assignment->user_id;
                $seller = User::where('id', $seller_id)->first();
                //  var_dump($seller);
                $tempAssignment['seller'] = "Name: " . $seller->name . ", WhatsApp No: " . $seller->phone . ",<br> Email: " . $seller->email;
                if ($seller->amount_deal_closed > 0) {
                    $tempAssignment['seller'] .= "<br>No of deal closed: " . $seller->no_deal_closed . ", amount of deal closed: " . number_format($seller->amount_deal_closed / 1000.0, 2) . " Thousands";
                }
            }
            $interestedAssignmentArr[] = $tempAssignment;
        }

        //  deal closed data for the  buyer 

        // deal closed company

        $dealClosedCompany = $buyer->companies()->where('deal_closed', 1)->orderBy('updated_at', 'desc')->get();;
        $dealClosedCompanyCompanyArr = array();
        foreach ($dealClosedCompany as $key => $eachCompany) {
            $tempCompany = $this->getCompanyDetail($eachCompany);
            $tempCompany['buyer_status'] = $eachCompany->pivot->is_active;

            if ($eachCompany->pivot->is_active === 'active') {
                $seller = User::find($eachCompany->user_id);
                if ($seller) {
                    $tempCompany['seller'] = "Name: {$seller->name}, WhatsApp No: {$seller->phone},<br> Email: {$seller->email}";
                    if ($seller->amount_deal_closed > 0) {
                        $tempCompany['seller'] .= "<br>No of deal closed: {$seller->no_deal_closed}, amount of deal closed: " . number_format($seller->amount_deal_closed / 1000, 2) . " Thousands";
                    }
                }
            }

            // Include only fields present in the JSON
            $tempCompany['type_of_entity'] = $eachCompany->type_of_entity;
            $tempCompany['name'] = $eachCompany->name;
            $tempCompany['name_prefix'] = $eachCompany->name_prefix;
            $tempCompany['roc'] = $eachCompany->roc;
            $tempCompany['year_of_incorporation'] = $eachCompany->year_of_incorporation;
            $tempCompany['industry'] = $eachCompany->industry;

            $tempCompany['have_gst'] = $eachCompany->have_gst;
            $tempCompany['current_market_price'] = $eachCompany->current_market_price;
            $tempCompany['high_52_weeks'] = $eachCompany->high_52_weeks;
            $tempCompany['low_52_weeks'] = $eachCompany->low_52_weeks;

            $tempCompany['promoters_holding'] = $eachCompany->promoters_holding;
            $tempCompany['transferable_holding'] = $eachCompany->transferable_holding;
            $tempCompany['public_holding'] = $eachCompany->public_holding;
            $tempCompany['market_capitalization'] = $eachCompany->market_capitalization;
            $tempCompany['trading_conditions'] = $eachCompany->trading_conditions;
            $tempCompany['acquisition_method'] = $eachCompany->acquisition_method;
            $tempCompany['face_value'] = $eachCompany->face_value;

            $tempCompany['no_of_directors'] = $eachCompany->no_of_directors;
            $tempCompany['no_of_promoters'] = $eachCompany->no_of_promoters;
            $tempCompany['demat_shareholding'] = $eachCompany->demat_shareholding;
            $tempCompany['physical_shareholding'] = $eachCompany->physical_shareholding;

            $tempCompany['authorised_capital'] = $eachCompany->authorised_capital;
            $tempCompany['authorised_capital_amount'] = $eachCompany->authorised_capital_amount;
            $tempCompany['paidup_capital'] = $eachCompany->paidup_capital;
            $tempCompany['paidup_capital_amount'] = $eachCompany->paidup_capital_amount;
            $tempCompany['activity_code'] = $eachCompany->activity_code;
            $tempCompany['type_of_NBFC'] = $eachCompany->type_of_NBFC;
            $tempCompany['size_of_NBFC'] = $eachCompany->size_of_NBFC;
            $tempCompany['ask_price_amount'] = $eachCompany->ask_price_amount;
            $tempCompany['market_capitalization_amount'] = $eachCompany->market_capitalization_amount;
            $tempCompany['market_capitalization_unit'] = $eachCompany->market_capitalization_unit;

            // Turnover
            for ($i = 1; $i <= 5; $i++) {
                $tempCompany["turnover_year{$i}"] = $eachCompany->{"turnover_year{$i}"};
                $tempCompany["turnover{$i}"] = $eachCompany->{"turnover{$i}"};
                $tempCompany["turnover_amount{$i}"] = $eachCompany->{"turnover_amount{$i}"};
                $tempCompany["turnover_unit{$i}"] = $eachCompany->{"turnover_unit{$i}"};
            }

            // Profit After Tax
            for ($i = 1; $i <= 5; $i++) {
                $tempCompany["profit_year{$i}"] = $eachCompany->{"profit_year{$i}"};
                $tempCompany["profit{$i}"] = $eachCompany->{"profit{$i}"};
                $tempCompany["profit_amount{$i}"] = $eachCompany->{"profit_amount{$i}"};
                $tempCompany["profit_unit{$i}"] = $eachCompany->{"profit_unit{$i}"};
            }

            // Balance Sheet
            $tempCompany['net_worth'] = $eachCompany->net_worth;
            $tempCompany['net_worth_amount'] = $eachCompany->net_worth_amount;
            $tempCompany['net_worth_unit'] = $eachCompany->net_worth_unit;
            $tempCompany['reserve_amount'] = $eachCompany->reserve_amount;
            $tempCompany['reserve_unit'] = $eachCompany->reserve_unit;
            $tempCompany['reserve'] = $eachCompany->reserve;
            $tempCompany['secured_creditors_amount'] = $eachCompany->secured_creditors_amount;
            $tempCompany['secured_creditors_unit'] = $eachCompany->secured_creditors_unit;
            $tempCompany['unsecured_creditors_amount'] = $eachCompany->unsecured_creditors_amount;
            $tempCompany['unsecured_creditors_unit'] = $eachCompany->unsecured_creditors_unit;
            $tempCompany['land_building_amount'] = $eachCompany->land_building_amount;
            $tempCompany['land_building_unit'] = $eachCompany->land_building_unit;
            $tempCompany['plant_machinery_amount'] = $eachCompany->plant_machinery_amount;
            $tempCompany['plant_machinery_unit'] = $eachCompany->plant_machinery_unit;
            $tempCompany['investment_amount'] = $eachCompany->investment_amount;
            $tempCompany['plant_machinery'] = $eachCompany->plant_machinery;
            $tempCompany['investment_unit'] = $eachCompany->investment_unit;
            $tempCompany['debtors_amount'] = $eachCompany->debtors_amount;
            $tempCompany['debtors_unit'] = $eachCompany->debtors_unit;
            $tempCompany['cash_bank_amount'] = $eachCompany->cash_bank_amount;
            $tempCompany['cash_bank_unit'] = $eachCompany->cash_bank_unit;

            // Compliance
            $tempCompany['roc_status'] = $eachCompany->roc_status;
            $tempCompany['roc_year'] = $eachCompany->roc_year;
            $tempCompany['income_tax_status'] = $eachCompany->income_tax_status;
            $tempCompany['income_tax_year'] = $eachCompany->income_tax_year;
            $tempCompany['gst_status'] = $eachCompany->gst_status;
            $tempCompany['gst_year'] = $eachCompany->gst_year;
            $tempCompany['rbi_status'] = $eachCompany->rbi_status;
            $tempCompany['rbi_year'] = $eachCompany->rbi_year;
            $tempCompany['fema_status'] = $eachCompany->fema_status;
            $tempCompany['fema_year'] = $eachCompany->fema_year;
            $tempCompany['sebi_status'] = $eachCompany->sebi_status;
            $tempCompany['sebi_year'] = $eachCompany->sebi_year;
            $tempCompany['stock_exchange_status'] = $eachCompany->stock_exchange_status;
            $tempCompany['stock_exchange_year'] = $eachCompany->stock_exchange_year;

            $dealClosedCompanyCompanyArr[] = $tempCompany;
        }



        // property deal closed

        $dealClosedProperty = $buyer->properties()->where('deal_closed', 1)->orderBy('updated_at', 'desc')->get();
        $dealClosedPropertyArr = array();
        foreach ($dealClosedProperty as $key => $eachProperty) {

            $tempProperty = $this->getPropertyDetail($eachProperty);
            $tempProperty['buyer_status'] = $eachProperty->pivot->is_active;
            if ($eachProperty->pivot->is_active == 'active') {
                $seller_id = $eachProperty->user_id;
                $seller = User::where('id', $seller_id)->first();
                $tempProperty['seller'] = "Name: " . $seller->name . ", WhatsApp No: " . $seller->phone . ", <br>Email: " . $seller->email;
                if ($seller->amount_deal_closed > 0) {
                    $tempProperty['seller'] .= "<br>No of deal closed: " . $seller->no_deal_closed . ", amount of deal closed: " . number_format($seller->amount_deal_closed / 1000.0, 2) . " Thousands";
                }
            }
            $dealClosedPropertyArr[] = $tempProperty;
        }



        // deal closed tradmark
        $dealClosedTrademarks = $buyer->noctrademarks()->where('deal_closed', 1)->orderBy('updated_at', 'desc')->get();
        $dealClosedTrademarkArr = array();
        foreach ($dealClosedTrademarks as $key => $trademark) {
            //   if($trademark->deal_closed ==1)
            //      continue;
            $tempTrademark = $this->getTrademarkDetail($trademark);
            $tempTrademark['buyer_status'] = $trademark->pivot->is_active;
            if ($trademark->pivot->is_active == 'active') {
                $seller_id = $trademark->user_id;
                $seller = User::where('id', $seller_id)->first();
                //  var_dump($seller);
                $tempTrademark['seller'] = "Name: " . $seller->name . ", WhatsApp No: " . $seller->phone . ",<br> Email: " . $seller->email;
                if ($seller->amount_deal_closed > 0) {
                    $tempTrademark['seller'] .= "<br>No of deal closed: " . $seller->no_deal_closed . ", amount of deal closed: " . number_format($seller->amount_deal_closed / 1000.0, 2) . " Thousands";
                }
            }
            $dealClosedTrademarkArr[] = $tempTrademark;
        }



        // deal closed assignment

        $dealClosedAssignments = $buyer->assignments()->where('deal_closed', 1)->orderBy('updated_at', 'desc')->get();
        $dealClosedAssignmentArr = array();
        foreach ($dealClosedAssignments as $key => $assignment) {
            //   if($assignment->deal_closed ==1)
            //      continue;
            $tempAssignment = $this->getAssignmentDetail($assignment);
            $tempAssignment['buyer_status'] = $assignment->pivot->is_active;
            if ($assignment->pivot->is_active == 'active') {
                $seller_id = $assignment->user_id;
                $seller = User::where('id', $seller_id)->first();
                //  var_dump($seller);
                $tempAssignment['seller'] = "Name: " . $seller->name . ", WhatsApp No: " . $seller->phone . ",<br> Email: " . $seller->email;
                if ($seller->amount_deal_closed > 0) {
                    $tempAssignment['seller'] .= "<br>No of deal closed: " . $seller->no_deal_closed . ", amount of deal closed: " . number_format($seller->amount_deal_closed / 1000.0, 2) . " Thousands";
                }
            }
            $dealClosedAssignmentArr[] = $tempAssignment;
        }


        return view('pages.user.buyer_dashboard', compact('interestedPropertyArr', 'interestedTrademarkArr', 'interestedCompanyArr', 'interestedAssignmentArr', 'dealClosedCompanyCompanyArr', 'dealClosedPropertyArr', 'dealClosedTrademarkArr', 'dealClosedAssignmentArr'));
    }
    public function company_filter()
    {
        return view('pages.user.buyer_filter_company');
    }



    public function noctrademark_filter()
    {
        return view('pages.user.buyer_filter_noctrademark');
    }
    public function company_filter_ajax(Request $request)
    {
        $buyer_id =  \Auth::guard('user')->id();
        $buyer = Buyer::findOrFail($buyer_id);
        $interestedCompany = $buyer->companies;

        $interestedCompanyArr = array();
        if (!empty($interestedCompany)) {
            foreach ($interestedCompany as $key => $eachCompany) {
                $interestedCompanyArr[] = $eachCompany->id;
            }
        }

        $filterCon = array();
        $filterCon[] = array('status', '=', 'active');
        if (!empty($request->input('type_of_entity'))) {
            $filterCon[] = array('type_of_entity', '=', $request->input('type_of_entity'));
        }

        if (!empty($request->input('roc'))) {
            $filterCon[] = array('roc', '=', $request->input('roc'));
        }
        if (!empty($request->input('year_of_incorporation'))) {
            $filterCon[] = array('year_of_incorporation', '=', $request->input('year_of_incorporation'));
        }
        if (!empty($request->input('industry'))) {
            $filterCon[] = array('industry', '=', $request->input('industry'));
        }

        if (!empty($request->input('ask_price_min'))) {
            $filterCon[] = array('ask_price_amount', '>=', $request->input('ask_price_min'));
        }
        if (!empty($request->input('ask_price_max'))) {
            $filterCon[] = array('ask_price_amount', '<=', $request->input('ask_price_max'));
        }

        $companies = Company::where($filterCon)->orderBy('updated_at', 'desc')->get();
        $companyArr = array();
        $filerData = array(
            'priceMin' => 0,
            'priceMax' => 0
        );
        $i = 1;
        foreach ($companies as $key => $company) {
            if ($i == 1) {
                $filerData['priceMin'] = $company->ask_price_amount;
                $filerData['priceMax'] = $company->ask_price_amount;
            } else {
                if ($company->ask_price_amount < $filerData['priceMin'])
                    $filerData['priceMin'] = $company->ask_price_amount;
                if ($company->ask_price_amount > $filerData['priceMax'])
                    $filerData['priceMax'] = $company->ask_price_amount;
            }
            $i++;
            if (in_array($company->id, $interestedCompanyArr)) {
                continue;
            }
            $companyArr[] = $this->getCompanyDetail($company);
        }
        //  echo "--->".count($companies);
        return view('pages.user.buyer_filter_company_ajax', compact('companyArr', 'filerData'));
    }


    public function noctrademark_filter_ajax(Request $request)
    {
        $buyer_id =  \Auth::guard('user')->id();
        $buyer = Buyer::findOrFail($buyer_id);
        $interestedNoctrademark = $buyer->noctrademarks;

        $interestedTrademarkArr = array();
        foreach ($interestedNoctrademark as $key => $eachTrademark) {
            $interestedTrademarkArr[] = $eachTrademark->id;
        }
        $filterCon = array();
        $filterCon[] = array('is_active', '=', 'active');
        if (!empty($request->input('class_no'))) {
            $filterCon[] = array('class_no', '=', $request->input('class_no'));
        }
        if (!empty($request->input('ask_price_min'))) {
            $filterCon[] = array('ask_price_amount', '>=', $request->input('ask_price_min'));
        }
        if (!empty($request->input('ask_price_max'))) {
            $filterCon[] = array('ask_price_amount', '<=', $request->input('ask_price_max'));
        }

        $nocTrademarks = NocTrademark::where($filterCon)->orderBy('updated_at', 'desc')->get();
        $trademarkArr = array();
        $filerData = array(
            'priceMin' => 0,
            'priceMax' => 0
        );
        $i = 1;
        foreach ($nocTrademarks as $key => $trademark) {
            if ($i == 1) {
                $filerData['priceMin'] = $trademark->ask_price_amount;
                $filerData['priceMax'] = $trademark->ask_price_amount;
            } else {
                if ($trademark->ask_price_amount < $filerData['priceMin'])
                    $filerData['priceMin'] = $trademark->ask_price_amount;
                if ($trademark->ask_price_amount > $filerData['priceMax'])
                    $filerData['priceMax'] = $trademark->ask_price_amount;
            }
            $i++;
            if (in_array($trademark->id, $interestedTrademarkArr)) {
                continue;
            }
            $trademarkArr[] = $this->getTrademarkDetail($trademark);
        }
        return view('pages.user.buyer_filter_noctrademark_ajax', compact('trademarkArr', 'filerData'));
    }
    public function property_filter()
    {
        return view('pages.user.buyer_filter_property');
    }
    public function property_filter_ajax(Request $request)
    {
        $buyer_id =  \Auth::guard('user')->id();
        $buyer = Buyer::findOrFail($buyer_id);
        $interestedProperty = $buyer->properties()->orderBy('properties.updated_at', 'desc')->get();

        $interestedPropertyArr = array();
        foreach ($interestedProperty as $key => $eachProperty) {
            $interestedPropertyArr[] = $eachProperty->id;
        }
        $filterCon = array();
        $filterCon[] = array('status', '=', 'active');
        if (!empty($request->input('state'))) {
            $filterCon[] = array('state', '=', $request->input('state'));
        }
        if (!empty($request->input('type'))) {
            $filterCon[] = array('type', '=', $request->input('type'));
        }
        if (!empty($request->input('space_min'))) {
            $filterCon[] = array('space', '>=', $request->input('space_min'));
        }
        if (!empty($request->input('space_max'))) {
            $filterCon[] = array('space', '<=', $request->input('space_max'));
        }

        if (!empty($request->input('ask_price_min'))) {
            $filterCon[] = array('ask_price_amount', '>=', $request->input('ask_price_min'));
        }
        if (!empty($request->input('ask_price_max'))) {
            $filterCon[] = array('ask_price_amount', '<=', $request->input('ask_price_max'));
        }
        $properties = Property::where($filterCon)->orderBy('updated_at', 'desc')->get();
        $propertyArr = array();
        $filerData = array(
            'priceMin' => 0,
            'priceMax' => 0,
            'spaceMin' => 0,
            'spaceMax' => 0
        );
        $i = 1;
        foreach ($properties as $key => $prop) {
            if ($i == 1) {
                $filerData['priceMin'] = $prop->ask_price_amount;
                $filerData['priceMax'] = $prop->ask_price_amount;
                $filerData['spaceMin'] = $prop->space;
                $filerData['spaceMax'] = $prop->space;
            } else {
                if ($prop->ask_price_amount < $filerData['priceMin'])
                    $filerData['priceMin'] = $prop->ask_price_amount;
                if ($prop->ask_price_amount > $filerData['priceMax'])
                    $filerData['priceMax'] = $prop->ask_price_amount;
                if ($prop->space < $filerData['spaceMin'])
                    $filerData['spaceMin'] = $prop->space;
                if ($prop->space > $filerData['spaceMax'])
                    $filerData['spaceMax'] = $prop->space;
            }
            if (in_array($prop->id, $interestedPropertyArr)) {
                continue;
            }
            $propertyArr[] = $this->getPropertyDetail($prop);
            $i++;
        }
        return view('pages.user.buyer_filter_property_ajax', compact('propertyArr', 'filerData'));
    }
    protected function getCompanyDetail($company)
    {
        return array(
            'id' => $company->id,
            'name' => $company->name,
            'name_prefix' => $company->name_prefix,
            'type_of_entity' => $company->type_of_entity,
            'roc' => $company->roc,
            'year_of_incorporation' => $company->year_of_incorporation,
            'industry' => $company->industry,
            'ask_price' => $company->ask_price,
            'ask_price_unit' => $company->ask_price_unit,
            'status' => ($company->deal_closed) ? "Deal Closed" : $company->status,
        );
    }

    protected function getTrademarkDetail($trademark)
    {
        return array(
            'id' => $trademark->id,
            'urn' => $trademark->urn,
            'wordmark' => $trademark->wordmark,
            'application_no' => $trademark->application_no,
            'class_no' => $trademark->class_no,
            'proprietor' => $trademark->proprietor,
            'status' => $trademark->status,
            'valid_upto' => date('j F, Y', strtotime($trademark->valid_upto)),
            'description' => $trademark->description,
            'ask_price' => $trademark->ask_price,
            'ask_price_unit' => $trademark->ask_price_unit,
            'is_active' => ($trademark->deal_closed) ? "Deal Closed" : $trademark->is_active,
        );
    }
    protected function getAssignmentDetail($assignment)
    {
        return array(
            'id' => $assignment->id,
            'urn' => $assignment->urn,
            'category' => $assignment->category,
            'subject' => $assignment->subject,
            'description' => $assignment->description,
            'deal_price' => $assignment->deal_price,
            'deal_price_unit' => $assignment->deal_price_unit,
            'is_active' => ($assignment->deal_closed) ? "Deal Closed" : $assignment->is_active,
            'deal_closed' => $assignment->deal_closed
        );
    }
    protected function getPropertyDetail($property)
    {

        return array(
            'id' => $property->id,
            'urn' => $property->urn,
            'space' => $property->space,
            'type' => $property->type,
            'ask_price' => $property->ask_price,
            'ask_price_unit' => $property->ask_price_unit,
            'ask_price_amount' => $property->ask_price_amount,
            'address' => $property->address,
            'pincode' => $property->pincode,
            'state' => $property->state,
            'status' => ($property->deal_closed) ? "Deal Closed" : $property->status,

        );
    }

    public function message(Request $request){
        $buyer_id =  \Auth::guard('user')->id();

       $message =  Message::where('user_id', $buyer_id)->orderby('id','desc')->get() ;
       return view('pages.user.message',compact('message'));

       
    }

}
