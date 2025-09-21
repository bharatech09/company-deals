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
        $buyer_id = \Auth::guard('user')->id();
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
        $buyer_id = \Auth::guard('user')->id();

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
        $buyer_id = \Auth::guard('user')->id();

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
        $buyer_id = \Auth::guard('user')->id();

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
        $buyer_id = \Auth::guard('user')->id();

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
        $buyer_id = \Auth::guard('user')->id();

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
        $buyer_id = \Auth::guard('user')->id();

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
        $buyer_id = \Auth::guard('user')->id();

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
        $buyer_id = \Auth::guard('user')->id();

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

            // Check if buyer has paid for this company
            $hasPaid = $this->hasPaidForCompany($buyer_id, $eachCompany->id);
            $tempCompany['has_paid'] = $hasPaid;

            if ($eachCompany->pivot->is_active === 'active') {
                $seller = User::find($eachCompany->user_id);
                if ($seller) {
                    $tempCompany['seller'] = "Name: {$seller->name}, WhatsApp No: {$seller->phone},<br> Email: {$seller->email}";
                    if ($seller->amount_deal_closed > 0) {
                        $tempCompany['seller'] .= "<br>No of deal closed: {$seller->no_deal_closed}, amount of deal closed: " . number_format($seller->amount_deal_closed / 1000, 2) . " Thousands";
                    }
                }
            } else {
                $amount = config('payments.seller_payment_amount'); // adjust key to yours
                $tempCompany['seller'] = "Pay ₹{$amount} to view seller details";
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

            // Check if buyer has paid for this property
            $hasPaid = $this->hasPaidForProperty($buyer_id, $eachProperty->id);
            $tempProperty['has_paid'] = $hasPaid;

            if ($eachProperty->pivot->is_active == 'active') {
                $seller_id = $eachProperty->user_id;
                $seller = User::where('id', $seller_id)->first();
                $tempProperty['seller'] = "Name: " . $seller->name . ", WhatsApp No: " . $seller->phone . ", <br>Email: " . $seller->email;
                if ($seller->amount_deal_closed > 0) {
                    $tempProperty['seller'] .= "<br>No of deal closed: " . $seller->no_deal_closed . ", amount of deal closed: " . number_format($seller->amount_deal_closed / 1000.0, 2) . " Thousands";
                }
            } else {
                 $tempCompany['seller'] = "Pay ₹{$amount} to view seller details";
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
            $tempTrademark['trademark_type'] = $trademark->trademark_type;

            // Check if buyer has paid for this trademark
            $hasPaid = $this->hasPaidForTrademark($buyer_id, $trademark->id);
            $tempTrademark['has_paid'] = $hasPaid;

            if ($trademark->pivot->is_active == 'active') {
                $seller_id = $trademark->user_id;
                $seller = User::where('id', $seller_id)->first();
                //  var_dump($seller);
                $tempTrademark['seller'] = "Name: " . $seller->name . ", WhatsApp No: " . $seller->phone . ",<br> Email: " . $seller->email;
                if ($seller->amount_deal_closed > 0) {
                    $tempTrademark['seller'] .= "<br>No of deal closed: " . $seller->no_deal_closed . ", amount of deal closed: " . number_format($seller->amount_deal_closed / 1000.0, 2) . " Thousands";
                }
            } else {
                $tempCompany['seller'] = "Pay ₹{$amount} to view seller details";
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

            // Check if buyer has paid for this assignment
            $hasPaid = $this->hasPaidForAssignment($buyer_id, $assignment->id);
            $tempAssignment['has_paid'] = $hasPaid;

            if ($assignment->pivot->is_active === 'active') {
                $seller = User::find($assignment->user_id);
                if ($seller) {
                    $tempAssignment['seller'] = "Name: {$seller->name}, WhatsApp No: {$seller->phone},<br> Email: {$seller->email}";
                    if ($seller->amount_deal_closed > 0) {
                        $tempAssignment['seller'] .= "<br>No of deal closed: {$seller->no_deal_closed}, amount of deal closed: " . number_format($seller->amount_deal_closed / 1000, 2) . " Thousands";
                    }
                }
            } else {
                $tempAssignment['seller'] = "Pay ₹2000 to view seller details";
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

            // Check if buyer has paid for this company
            $hasPaid = $this->hasPaidForCompany($buyer_id, $eachCompany->id);
            $tempCompany['has_paid'] = $hasPaid;

            if ($eachCompany->pivot->is_active === 'active') {
                $seller = User::find($eachCompany->user_id);
                if ($seller) {
                    $tempCompany['seller'] = "Name: {$seller->name}, WhatsApp No: {$seller->phone},<br> Email: {$seller->email}";
                    if ($seller->amount_deal_closed > 0) {
                        $tempCompany['seller'] .= "<br>No of deal closed: {$seller->no_deal_closed}, amount of deal closed: " . number_format($seller->amount_deal_closed / 1000, 2) . " Thousands";
                    }
                }
            } else {
                $tempCompany['seller'] = "Pay ₹2000 to view seller details";
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

            // Check if buyer has paid for this property
            $hasPaid = $this->hasPaidForProperty($buyer_id, $eachProperty->id);
            $tempProperty['has_paid'] = $hasPaid;

            if ($eachProperty->pivot->is_active == 'active') {
                $seller_id = $eachProperty->user_id;
                $seller = User::where('id', $seller_id)->first();
                $tempProperty['seller'] = "Name: " . $seller->name . ", WhatsApp No: " . $seller->phone . ", <br>Email: " . $seller->email;
                if ($seller->amount_deal_closed > 0) {
                    $tempProperty['seller'] .= "<br>No of deal closed: " . $seller->no_deal_closed . ", amount of deal closed: " . number_format($seller->amount_deal_closed / 1000.0, 2) . " Thousands";
                }
            } else {
                $tempProperty['seller'] = "Pay ₹2000 to view seller details";
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

            // Check if buyer has paid for this trademark
            $hasPaid = $this->hasPaidForTrademark($buyer_id, $trademark->id);
            $tempTrademark['has_paid'] = $hasPaid;

            if ($trademark->pivot->is_active == 'active') {
                $seller_id = $trademark->user_id;
                $seller = User::where('id', $seller_id)->first();
                //  var_dump($seller);
                $tempTrademark['seller'] = "Name: " . $seller->name . ", WhatsApp No: " . $seller->phone . ",<br> Email: " . $seller->email;
                if ($seller->amount_deal_closed > 0) {
                    $tempTrademark['seller'] .= "<br>No of deal closed: " . $seller->no_deal_closed . ", amount of deal closed: " . number_format($seller->amount_deal_closed / 1000.0, 2) . " Thousands";
                }
            } else {
                $tempCompany['seller'] = "Pay ₹{$amount} to view seller details";
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

            // Check if buyer has paid for this assignment
            $hasPaid = $this->hasPaidForAssignment($buyer_id, $assignment->id);
            $tempAssignment['has_paid'] = $hasPaid;

            if ($assignment->pivot->is_active == 'active') {
                $seller_id = $assignment->user_id;
                $seller = User::where('id', $seller_id)->first();
                //  var_dump($seller);
                $tempAssignment['seller'] = "Name: " . $seller->name . ", WhatsApp No: " . $seller->phone . ",<br> Email: " . $seller->email;
                if ($seller->amount_deal_closed > 0) {
                    $tempAssignment['seller'] .= "<br>No of deal closed: " . $seller->no_deal_closed . ", amount of deal closed: " . number_format($seller->amount_deal_closed / 1000.0, 2) . " Thousands";
                }
            } else {
                $tempAssignment['seller'] = "Pay ₹2000 to view seller details";
            }
            $dealClosedAssignmentArr[] = $tempAssignment;
        }


        // Payment check for seller details (one-time)
        // $hasPaid = \App\Models\Payment::where([
        //     ['user_id', '=', $buyer_id],
        //     ['service_type', '=', 'buyer_unlock_seller_details'],
        //     ['status', '=', 'paid'],
        // ])->exists();

        return view('pages.user.buyer_dashboard', compact(
            'interestedPropertyArr',
            'interestedTrademarkArr',
            'interestedCompanyArr',
            'interestedAssignmentArr',
            'dealClosedCompanyCompanyArr',
            'dealClosedPropertyArr',
            'dealClosedTrademarkArr',
            'dealClosedAssignmentArr'
        ));
    }
    public function company_filter()
    {
        $companies = Company::get(); // Your custom filter logic

        $min_price = $companies->min('ask_price') ?? 0;
        $max_price = $companies->max('ask_price') ?? 1000000;


        $data['ask_price_min'] =  $min_price;
        $data['ask_price_max'] =  $max_price;

        return view('pages.user.buyer_filter_company', $data);
    }



    public function noctrademark_filter()
    {
        return view('pages.user.buyer_filter_noctrademark');
    }
    public function company_filter_ajax(Request $request)
    {
        $buyer_id = \Auth::guard('user')->id();
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
                $filerData['priceMin'] = $company->ask_price_amount ?? 0;
                $filerData['priceMax'] = $company->ask_price_amount ?? 0;
            } else {
                if ($company->ask_price_amount < $filerData['priceMin'])
                    $filerData['priceMin'] = $company->ask_price_amount ?? 0;
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
        $buyer_id = \Auth::guard('user')->id();
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
        $buyer_id = \Auth::guard('user')->id();
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
        if ($filerData['priceMin'] ==  $filerData['priceMax']) {
            $filerData['priceMax'] += 1000;
        }
        if ($filerData['spaceMin'] ==  $filerData['spaceMax']) {
            $filerData['spaceMax'] += 1000;
        }
        return view('pages.user.buyer_filter_property_ajax', compact('propertyArr', 'filerData'));
    }
    protected function getCompanyDetail($company)
    {
        $data = [
            'id' => $company->id,
            'name' => $company->name,
            'name_prefix' => $company->name_prefix,
            'type_of_entity' => $company->type_of_entity,
            'roc' => $company->roc,
            'year_of_incorporation' => $company->year_of_incorporation,
            'industry' => $company->industry,
            'ask_price' => $company->ask_price,
            'ask_price_unit' => $company->ask_price_unit,
            'ask_price_amount' => $company->ask_price_amount,
            'status' => ($company->deal_closed) ? "Deal Closed" : $company->status,

            // Basic Financial Details
            'have_gst' => $company->have_gst,
            'current_market_price' => $company->current_market_price,
            'high_52_weeks' => $company->high_52_weeks,
            'low_52_weeks' => $company->low_52_weeks,
            'promoters_holding' => $company->promoters_holding,
            'transferable_holding' => $company->transferable_holding,
            'public_holding' => $company->public_holding,
            'market_capitalization' => $company->market_capitalization,
            'market_capitalization_amount' => $company->market_capitalization,
            'market_capitalization_unit' => $company->market_capitalization_unit,
            'trading_conditions' => $company->trading_conditions,
            'acquisition_method' => $company->acquisition_method,
            'face_value' => $company->face_value,

            // Company Structure
            'no_of_directors' => $company->no_of_directors,
            'no_of_promoters' => $company->no_of_promoters,
            'demat_shareholding' => $company->demat_shareholding,
            'physical_shareholding' => $company->physical_shareholding,
            'authorised_capital' => $company->authorised_capital,
            'authorised_capital_amount' => $company->authorised_capital,
            'authorized_capital' => $company->authorised_capital, // alias for Blade
            'authorized_capital_unit' => $company->authorized_capital_unit,
            'paidup_capital' => $company->paidup_capital,
            'paidup_capital_amount' => $company->paidup_capital,
            'paid_up_capital' => $company->paidup_capital, // alias
            'paid_up_capital_unit' => $company->paid_up_capital_unit,
            'activity_code' => $company->activity_code,
            'type_of_NBFC' => $company->type_of_NBFC,
            'size_of_NBFC' => $company->size_of_NBFC,

            // Balance Sheet
            'net_worth' => $company->net_worth,
            'net_worth_amount' => $company->net_worth,
            'net_worth_unit' => $company->net_worth_unit,
            'reserve' => $company->reserve,
            'reserve_amount' => $company->reserve,
            'reserve_unit' => $company->reserve_unit,

            'secured_creditors_amount' => $company->secured_creditors,
            'secured_creditors' => $company->secured_creditors,
            'secured_creditors_unit' => $company->secured_creditors_unit,

            'unsecured_creditors_amount' => $company->unsecured_creditors,
            'unsecured_creditors' => $company->unsecured_creditors,
            'unsecured_creditors_unit' => $company->unsecured_creditors_unit,

            'land_building_amount' => $company->land_building,
            'land_building' => $company->land_building,
            'land_building_unit' => $company->land_building_unit,

            'plant_machinery' => $company->plant_machinery,
            'plant_machinery_amount' => $company->plant_machinery,
            'plant_machinery_unit' => $company->plant_machinery_unit,

            'investment' => $company->investment,
            'investment_amount' => $company->investment,
            'investment_unit' => $company->investment_unit,

            'debtors' => $company->debtors,
            'debtors_amount' => $company->debtors,
            'debtors_unit' => $company->debtors_unit,

            'cash_bank' => $company->cash_bank,
            'cash_bank_amount' => $company->cash_bank,
            'cash_bank_unit' => $company->cash_bank_unit,

            // Compliance
            'roc_status' => $company->roc_status,
            'roc_year' => $company->roc_year,
            'income_tax_status' => $company->income_tax_status,
            'income_tax_year' => $company->income_tax_year,
            'gst_status' => $company->gst_status,
            'gst_year' => $company->gst_year,
            'rbi_status' => $company->rbi_status,
            'rbi_year' => $company->rbi_year,
            'fema_status' => $company->fema_status,
            'fema_year' => $company->fema_year,
            'sebi_status' => $company->sebi_status,
            'sebi_year' => $company->sebi_year,
            'stock_exchange_status' => $company->stock_exchange_status,
            'stock_exchange_year' => $company->stock_exchange_year,
            'certicate_status' => $company->certicate_status,
            'certicate_year' => $company->certicate_year,
        ];

        // Add Turnover and Profit details
        for ($i = 1; $i <= 5; $i++) {
            $data["turnover_year{$i}"] = $company->{"turnover_year{$i}"};
            $data["turnover{$i}"] = $company->{"turnover{$i}"};
            $data["turnover_amount{$i}"] = $company->{"turnover_amount{$i}"};
            $data["turnover_unit{$i}"] = $company->{"turnover_unit{$i}"};

            $data["profit_year{$i}"] = $company->{"profit_year{$i}"};
            $data["profit{$i}"] = $company->{"profit{$i}"};
            $data["profit_amount{$i}"] = $company->{"profit_amount{$i}"};
            $data["profit_unit{$i}"] = $company->{"profit_unit{$i}"};
        }

        return $data;
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
            'trademark_type' => $trademark->trademark_type,
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
            'assignment_pricing_type' => $assignment->assignment_pricing_type,
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
            'property_typex' => $property->property_type,
            'status' => ($property->deal_closed) ? "Deal Closed" : $property->status,

        );
    }

    public function message(Request $request)
    {
        $buyer_id = \Auth::guard('user')->id();

        $message = Message::where('user_id', $buyer_id)->orderby('id', 'desc')->get();
        return view('pages.user.message', compact('message'));
    }

    /**
     * Check if the buyer has paid for viewing seller details (one-time payment)
     */
    protected function hasPaidForSellerDetails($buyerId)
    {
        return \App\Models\Payment::where([
            ['user_id', '=', $buyerId],
            ['service_type', '=', 'buyer_unlock_seller_details'],
            ['status', '=', 'paid'],
        ])->exists();
    }

    /**
     * Check if buyer has paid for a specific property
     */
    protected function hasPaidForProperty($buyerId, $propertyId)
    {
        $buyerPropertyId = \DB::table('buyer_property')
            ->where('property_id', $propertyId)
            ->where('buyer_id', $buyerId)
            ->value('id');

        if (!$buyerPropertyId) {
            return false;
        }

        return \App\Models\Payment::where([
            ['user_id', '=', $buyerId],
            ['service_type', '=', 'buyer_property'],
            ['service_id', '=', $buyerPropertyId],
            ['status', '=', 'paid'],
        ])->exists();
    }

    /**
     * Check if buyer has paid for a specific trademark
     */
    protected function hasPaidForTrademark($buyerId, $trademarkId)
    {
        $buyerTrademarkId = \DB::table('buyer_noc_trademark')
            ->where('noc_trademark_id', $trademarkId)
            ->where('buyer_id', $buyerId)
            ->value('id');

        if (!$buyerTrademarkId) {
            return false;
        }

        return \App\Models\Payment::where([
            ['user_id', '=', $buyerId],
            ['service_type', '=', 'buyer_trademark'],
            ['service_id', '=', $buyerTrademarkId],
            ['status', '=', 'paid'],
        ])->exists();
    }

    /**
     * Check if buyer has paid for a specific company
     */
    protected function hasPaidForCompany($buyerId, $companyId)
    {
        $buyerCompanyId = \DB::table('buyer_company')
            ->where('company_id', $companyId)
            ->where('buyer_id', $buyerId)
            ->value('id');

        if (!$buyerCompanyId) {
            return false;
        }

        return \App\Models\Payment::where([
            ['user_id', '=', $buyerId],
            ['service_type', '=', 'buyer_company'],
            ['service_id', '=', $buyerCompanyId],
            ['status', '=', 'paid'],
        ])->exists();
    }

    /**
     * Check if buyer has paid for a specific assignment
     */
    protected function hasPaidForAssignment($buyerId, $assignmentId)
    {
        $buyerAssignmentId = \DB::table('assignment_buyer')
            ->where('assignment_id', $assignmentId)
            ->where('buyer_id', $buyerId)
            ->value('id');

        if (!$buyerAssignmentId) {
            return false;
        }

        return \App\Models\Payment::where([
            ['user_id', '=', $buyerId],
            ['service_type', '=', 'buyer_assignment'],
            ['service_id', '=', $buyerAssignmentId],
            ['status', '=', 'paid'],
        ])->exists();
    }

    /**
     * Show payment form for viewing seller details
     */
    public function showSellerDetailsPaymentForm(Request $request)
    {
        $type = $request->query('type', 'global');
        $id = $request->query('id', null);
        return view('pages.user.buyer_payment', compact('type', 'id'));
    }

    /**
     * Process payment for viewing seller details
     */
    public function processSellerDetailsPayment(Request $request)
    {
        \Log::debug('Buyer payment initiation', [
            'request' => $request->all(),
        ]);
        $amount = config('payments.seller_payment_amount'); // adjust key to yours
        // $amount = 2000; // Fixed amount for buyer payments
        $type = $request->input('type', 'property');
        $itemId = $request->input('item_id', null);

        // Handle property, company, trademark, and assignment payments
        if (!in_array($type, ['property', 'company', 'trademark', 'assignment'])) {
            return back()->with('error', 'Only property, company, trademark, and assignment payments are supported at this time.');
        }

        $appId = config('services.cashfree.app_id');
        $secretKey = config('services.cashfree.secret_key');
        $user = \Auth::guard('user')->user();
        $orderData = [
            "order_amount" => $amount,
            "order_currency" => "INR",
            "customer_details" => [
                "customer_id" => str_replace(['@', '.'], '_', $user->email),
                "customer_phone" => $user->phone,
                "customer_email" => $user->email,
                "customer_name" => $user->name ?? "Customer"
            ],
            "order_note" => "Buyer {$type} Payment for Seller Details ({$type} ID: $itemId)",
            "order_meta" => [
                "return_url" => route('user.buyer.pay.return') . "?order_id={order_id}&type=$type&item_id=$itemId"
            ],
            "checkout_mode" => "REDIRECT"
        ];
        \Log::debug('Cashfree orderData', $orderData);
        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'x-client-id' => $appId,
            'x-client-secret' => $secretKey,
            'x-api-version' => '2025-01-01',
            'Content-Type' => 'application/json',
        ])->post('https://sandbox.cashfree.com/pg/orders', $orderData);
        $body = $response->json();
        \Log::debug('Cashfree response', ['body' => $body]);
        if (isset($body['payment_session_id'])) {
            return view('pages.user.payment_session', [
                'paymentSessionId' => $body['payment_session_id'],
                'type' => $type,
                'item_id' => $itemId
            ]);
        }
        \Log::error('Cashfree session creation failed', ['body' => $body]);
        return back()->with('error', $body['message'] ?? 'Cashfree session creation failed.');
    }

    /**
     * Handle payment return from Cashfree
     */
    public function sellerDetailsPaymentReturn(Request $request)
    {
        \Log::debug('Buyer payment return', [
            'query' => $request->query(),
            'input' => $request->all(),
            'all_params' => $request->all(),
            'auth_check' => \Auth::guard('user')->check(),
            'auth_guard' => \Auth::getDefaultDriver(),
            'user_id' => \Auth::guard('user')->id(),
            'session_role' => session('role'),
        ]);

        // Try to get order_id from multiple sources
        $orderId = $request->query('order_id') ??
            $request->input('order_id') ??
            $request->query('cf_order_id') ??
            $request->input('cf_order_id');

        $type = $request->query('type', 'property');
        $itemId = $request->query('item_id', null);
        $appId = config('services.cashfree.app_id');
        $secretKey = config('services.cashfree.secret_key');

        if (!$orderId) {
            \Log::error('Invalid payment return - no order_id found', [
                'query' => $request->query(),
                'input' => $request->all(),
                'headers' => $request->headers->all()
            ]);
            return redirect()->route('user.login')->with('error', 'Invalid payment return - no order ID found.');
        }

        // Check if user is authenticated with user guard
        if (!\Auth::guard('user')->check()) {
            \Log::error('User not authenticated for payment return', [
                'order_id' => $orderId,
                'type' => $type,
                'item_id' => $itemId
            ]);
            return redirect()->route('user.login')->with('error', 'Please login to complete your payment.');
        }

        // Check if user has buyer role
        if (session('role') !== 'buyer') {
            \Log::error('User does not have buyer role for payment return', [
                'order_id' => $orderId,
                'type' => $type,
                'item_id' => $itemId,
                'user_role' => session('role'),
                'user_id' => \Auth::guard('user')->id()
            ]);
            return redirect()->route('user.login')->with('error', 'Please login as buyer to complete your payment.');
        }

        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->get("https://sandbox.cashfree.com/pg/orders/{$orderId}", [
                'headers' => [
                    'x-client-id' => $appId,
                    'x-client-secret' => $secretKey,
                    'x-api-version' => '2025-01-01',
                    'Content-Type' => 'application/json',
                ],
            ]);
            $body = json_decode($response->getBody(), true);
            \Log::debug('Cashfree order status response', ['body' => $body]);

            // Log the raw response for debugging
            \Log::debug('Cashfree raw response', [
                'status_code' => $response->getStatusCode(),
                'headers' => $response->getHeaders(),
                'body' => $response->getBody()->getContents()
            ]);

            // Find the buyer record based on type
            $buyerRecordId = null;
            $serviceType = '';
            $buyer_id = \Auth::guard('user')->id();

            if ($type === 'property') {
                $buyerRecordId = \DB::table('buyer_property')
                    ->where('property_id', $itemId)
                    ->where('buyer_id', $buyer_id)
                    ->value('id');
                $serviceType = 'buyer_property';
            } elseif ($type === 'company') {
                $buyerRecordId = \DB::table('buyer_company')
                    ->where('company_id', $itemId)
                    ->where('buyer_id', $buyer_id)
                    ->value('id');
                $serviceType = 'buyer_company';
            } elseif ($type === 'trademark') {
                $buyerRecordId = \DB::table('buyer_noc_trademark')
                    ->where('noc_trademark_id', $itemId)
                    ->where('buyer_id', $buyer_id)
                    ->value('id');
                $serviceType = 'buyer_trademark';
            } elseif ($type === 'assignment') {
                $buyerRecordId = \DB::table('assignment_buyer')
                    ->where('assignment_id', $itemId)
                    ->where('buyer_id', $buyer_id)
                    ->value('id');
                $serviceType = 'buyer_assignment';
            }

            if (!$buyerRecordId) {
                \Log::error('Buyer record not found', [
                    'type' => $type,
                    'item_id' => $itemId,
                    'user_id' => $buyer_id,
                    'auth_check' => \Auth::guard('user')->check(),
                    'available_properties' => \DB::table('buyer_property')->where('buyer_id', $buyer_id)->get(['id', 'property_id']),
                    'available_companies' => \DB::table('buyer_company')->where('buyer_id', $buyer_id)->get(['id', 'company_id']),
                    'available_trademarks' => \DB::table('buyer_noc_trademark')->where('buyer_id', $buyer_id)->get(['id', 'noc_trademark_id'])
                ]);
                return redirect()->route('user.buyer.dashboard')->with('error', ucfirst($type) . ' record not found. Please contact support.');
            }

            // Check if payment already exists for this order
            $existingPayment = \App\Models\Payment::where('notes', 'like', '%"order_id":"' . $orderId . '"%')->first();
            if ($existingPayment) {
                \Log::info('Payment already exists for order', ['order_id' => $orderId, 'payment_id' => $existingPayment->id]);
                return redirect()->route('user.buyer.dashboard')->with('status', 'Payment already processed successfully!');
            }
$amount = config('payments.seller_payment_amount'); // adjust key to yours

            // Save payment in DB
            $payment = \App\Models\Payment::create([
                'user_id' => $buyer_id,
                'amount' => $body['order_amount'] ?? $amount,
                'status' => $body['order_status'] ?? 'unknown',
                'payment_method' => $body['payment_method'] ?? 'cashfree',
                'transaction_id' => $body['payment_id'] ?? null,
                'service_type' => $serviceType,
                'service_id' => $buyerRecordId,
                'service_start_date' => now(),
                'service_end_date' => now()->addYear(), // 1 year validity
                'payment_from' => 'buyer',
                'payment_type' => 'Online',
                'notes' => json_encode([
                    'type' => $type,
                    'item_id' => $itemId,
                    'cashfree' => $body,
                    'order_id' => $orderId,
                ]),
            ]);

            \Log::info('Buyer payment created', [
                'user_id' => $buyer_id,
                'type' => $type,
                'item_id' => $itemId,
                'order_id' => $orderId,
                'amount' => $body['order_amount'] ?? 2000,
                'status' => $body['order_status'] ?? 'unknown',
                'payment_db_id' => $payment->id ?? null,
                'buyer_record_id' => $buyerRecordId,
            ]);

            // Check for successful payment status (handle different possible values)
            $paymentStatus = strtolower($body['order_status'] ?? '');
            $successfulStatuses = ['paid', 'success', 'completed', 'successful'];

            if (in_array($paymentStatus, $successfulStatuses)) {
                // Update the buyer record status to active
                $updateResult = false;
                if ($type === 'property') {
                    $updateResult = \DB::table('buyer_property')
                        ->where('id', $buyerRecordId)
                        ->update(['is_active' => 'active']);
                } elseif ($type === 'company') {
                    $updateResult = \DB::table('buyer_company')
                        ->where('id', $buyerRecordId)
                        ->update(['is_active' => 'active']);
                } elseif ($type === 'trademark') {
                    $updateResult = \DB::table('buyer_noc_trademark')
                        ->where('id', $buyerRecordId)
                        ->update(['is_active' => 'active']);
                } elseif ($type === 'assignment') {
                    $updateResult = \DB::table('assignment_buyer')
                        ->where('id', $buyerRecordId)
                        ->update(['is_active' => 'active']);
                }

                \Log::info('Buyer record status updated', [
                    'type' => $type,
                    'buyer_record_id' => $buyerRecordId,
                    'update_result' => $updateResult,
                    'new_status' => 'active'
                ]);

                return redirect()->route('user.buyer.dashboard')->with('status', "Payment successful! You can now view seller details for this {$type}.");
            } else {
                \Log::warning('Payment not successful', [
                    'order_id' => $orderId,
                    'status' => $body['order_status'],
                    'payment_id' => $payment->id
                ]);
                return redirect()->route('user.buyer.dashboard')->with('error', 'Payment not successful. Status: ' . ($body['order_status'] ?? 'unknown'));
            }
        } catch (\Exception $e) {
            \Log::error('Payment verification failed', [
                'exception' => $e->getMessage(),
                'order_id' => $orderId,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('user.buyer.dashboard')->with('error', 'Payment verification failed: ' . $e->getMessage());
        }
    }

    /**
     * Handle Cashfree webhook for payment notifications
     */
    public function cashfreeWebhook(Request $request)
    {
        \Log::debug('Cashfree webhook received', [
            'headers' => $request->headers->all(),
            'body' => $request->all(),
        ]);

        try {
            $orderId = $request->input('orderId');
            $orderAmount = $request->input('orderAmount');
            $orderStatus = $request->input('orderStatus');
            $paymentMode = $request->input('paymentMode');
            $customerId = $request->input('customerId');

            if (!$orderId) {
                \Log::error('Webhook missing orderId');
                return response()->json(['status' => 'error', 'message' => 'Missing orderId'], 400);
            }

            // Check if payment already exists for this order
            $existingPayment = \App\Models\Payment::where('notes', 'like', '%"order_id":"' . $orderId . '"%')->first();
            if ($existingPayment) {
                \Log::info('Payment already exists for order in webhook', ['order_id' => $orderId, 'payment_id' => $existingPayment->id]);
                return response()->json(['status' => 'success', 'message' => 'Payment already processed']);
            }

            // Extract property_id from customerId or other fields
            $propertyId = null;
            $type = 'property';

            // Try to find the buyer property record for this user
            $user = \App\Models\User::where('email', 'like', '%' . str_replace('_', '@', $customerId) . '%')->first();
            if ($user) {
                $buyerPropertyId = \DB::table('buyer_property')
                    ->where('buyer_id', $user->id)
                    ->where('is_active', '!=', 'active')
                    ->first();

                if ($buyerPropertyId) {
                    $propertyId = $buyerPropertyId->property_id;
                }
            }

            if (!$propertyId) {
                \Log::error('Could not determine property_id from webhook', ['order_id' => $orderId, 'customer_id' => $customerId]);
                return response()->json(['status' => 'error', 'message' => 'Could not determine property_id'], 400);
            }

            // Create payment record
            $payment = \App\Models\Payment::create([
                'user_id' => $user->id,
                'amount' => $orderAmount,
                'status' => $orderStatus,
                'payment_method' => $paymentMode ?? 'cashfree',
                'transaction_id' => $orderId,
                'service_type' => 'buyer_property',
                'service_id' => $buyerPropertyId->id,
                'service_start_date' => now(),
                'service_end_date' => now()->addYear(),
                'payment_from' => 'buyer',
                'payment_type' => 'Online',
                'notes' => json_encode([
                    'type' => $type,
                    'property_id' => $propertyId,
                    'webhook_data' => $request->all(),
                    'order_id' => $orderId,
                ]),
            ]);

            // Check for successful payment status
            $paymentStatus = strtolower($orderStatus);
            $successfulStatuses = ['paid', 'success', 'completed', 'successful'];

            if (in_array($paymentStatus, $successfulStatuses)) {
                // Update the buyer property status to active
                $updateResult = \DB::table('buyer_property')
                    ->where('id', $buyerPropertyId->id)
                    ->update(['is_active' => 'active']);

                \Log::info('Webhook: Buyer property status updated', [
                    'buyer_property_id' => $buyerPropertyId->id,
                    'update_result' => $updateResult,
                    'new_status' => 'active'
                ]);
            }

            \Log::info('Webhook payment processed successfully', [
                'order_id' => $orderId,
                'payment_id' => $payment->id,
                'status' => $orderStatus
            ]);

            return response()->json(['status' => 'success', 'message' => 'Payment processed successfully']);
        } catch (\Exception $e) {
            \Log::error('Webhook processing failed', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['status' => 'error', 'message' => 'Processing failed'], 500);
        }
    }

    /**
     * Test method to manually process payment (for debugging)
     */
    public function testPaymentProcessing(Request $request)
    {
        $amount = config('payments.seller_payment_amount'); // adjust key to yours
        $orderId = $request->input('order_id');
        $itemId = $request->input('item_id');
        $type = $request->input('type', 'property');
        $buyer_id = \Auth::guard('user')->id();

        if (!$orderId || !$itemId) {
            return response()->json(['error' => 'Missing order_id or item_id'], 400);
        }

        try {
            // Find the buyer record based on type
            $buyerRecordId = null;
            $serviceType = '';

            if ($type === 'property') {
                $buyerRecordId = \DB::table('buyer_property')
                    ->where('property_id', $itemId)
                    ->where('buyer_id', $buyer_id)
                    ->value('id');
                $serviceType = 'buyer_property';
            } elseif ($type === 'company') {
                $buyerRecordId = \DB::table('buyer_company')
                    ->where('company_id', $itemId)
                    ->where('buyer_id', $buyer_id)
                    ->value('id');
                $serviceType = 'buyer_company';
            } elseif ($type === 'trademark') {
                $buyerRecordId = \DB::table('buyer_noc_trademark')
                    ->where('noc_trademark_id', $itemId)
                    ->where('buyer_id', $buyer_id)
                    ->value('id');
                $serviceType = 'buyer_trademark';
            } elseif ($type === 'assignment') {
                $buyerRecordId = \DB::table('assignment_buyer')
                    ->where('assignment_id', $itemId)
                    ->where('buyer_id', $buyer_id)
                    ->value('id');
                $serviceType = 'buyer_assignment';
            }

            if (!$buyerRecordId) {
                return response()->json(['error' => ucfirst($type) . ' record not found'], 404);
            }

            // Create payment record
            $payment = \App\Models\Payment::create([
                'user_id' => $buyer_id,
                'amount' => $amount,
                'status' => 'paid',
                'payment_method' => 'test',
                'transaction_id' => $orderId,
                'service_type' => $serviceType,
                'service_id' => $buyerRecordId,
                'service_start_date' => now(),
                'service_end_date' => now()->addYear(),
                'payment_from' => 'buyer',
                'payment_type' => 'Test',
                'notes' => json_encode([
                    'type' => $type,
                    'item_id' => $itemId,
                    'order_id' => $orderId,
                    'test' => true,
                ]),
            ]);

            // Update the buyer record status to active
            $updateResult = false;
            if ($type === 'property') {
                $updateResult = \DB::table('buyer_property')
                    ->where('id', $buyerRecordId)
                    ->update(['is_active' => 'active']);
            } elseif ($type === 'company') {
                $updateResult = \DB::table('buyer_company')
                    ->where('id', $buyerRecordId)
                    ->update(['is_active' => 'active']);
            } elseif ($type === 'trademark') {
                $updateResult = \DB::table('buyer_noc_trademark')
                    ->where('id', $buyerRecordId)
                    ->update(['is_active' => 'active']);
            } elseif ($type === 'assignment') {
                $updateResult = \DB::table('assignment_buyer')
                    ->where('id', $buyerRecordId)
                    ->update(['is_active' => 'active']);
            }

            return response()->json([
                'success' => true,
                'payment_id' => $payment->id,
                'buyer_record_id' => $buyerRecordId,
                'type' => $type,
                'update_result' => $updateResult,
                'message' => ucfirst($type) . ' payment processed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Processing failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
