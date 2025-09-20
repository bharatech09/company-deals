<?php

namespace App\Models;

use App\Models\User;
use App\Models\Buyer;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class NocTrademark extends Model
{
        use Sortable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'urn',
        'user_id',
        'wordmark',
        'application_no',
        'class_no',
        'proprietor',
        'status',
        'valid_upto',
        'description',
        'ask_price',
        'ask_price_unit',
        'ask_price_amount',
        'deal_closed',
        'buyer_id',
        'status',
        'payment_id',
        'is_active',
        'deal_closed',
        'trademark_type',
        'home_featured'
    ];

        public $sortable = [
        'urn', 'wordmark', 'application_no', 'class_no', 'status',
        'proprietor', 'ask_price', 'is_active', 'approved', 'created_at'
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
        $payments = self::join('payments', 'noc_trademarks.id', '=', 'payments.service_id')->where('service_id', '=', $this->id)->where('service_type', '=', 'seller_trademark')->select('payments.*')->orderBy('created_at', 'desc')->get();
        return $payments;
    }
    public static function seller_noctrademark($condition, $third =  false)
    {
        $user =  \Auth::guard('user')->user();
        if ($condition == "all") {
            $nocTrademarks = $user->nocTrademarks;
        } else {
            $nocTrademarks = $user->nocTrademarks()->where(function ($q) {
                $q->where('noc_trademarks.deal_closed', 0)
                    ->orWhereNull(column: 'noc_trademarks.deal_closed');   // 0 या NULL दोनों ऐक्सेप्ट
            })->where('noc_trademarks.is_active', $condition)->get();
        }


        if ($third == true) {
            $nocTrademarks = $user->nocTrademarks()->where('noc_trademarks.deal_closed', 1)->get();
        }
        $arrTrademark = array();
        foreach ($nocTrademarks as $eachTrademark) {
            $tempArr = array(
                'id' => $eachTrademark->id,
                'urn' => $eachTrademark->urn,
                'wordmark' => $eachTrademark->wordmark,
                'application_no' => $eachTrademark->application_no,
                'class_no' => $eachTrademark->class_no,
                'proprietor' => $eachTrademark->proprietor,
                'status' => $eachTrademark->status,
                'valid_upto' => $eachTrademark->valid_upto,
                'description' => $eachTrademark->description,
                'ask_price' => $eachTrademark->ask_price,
                'ask_price_unit' => $eachTrademark->ask_price_unit,
                'buyer_id' => $eachTrademark->buyer_id,
                'approved' => $eachTrademark->approved,
                'payment_id' => $eachTrademark->payment_id,
                'trademark_type' => $eachTrademark->trademark_type,
                'is_active' => ($eachTrademark->deal_closed) ? "Deal Closed" : $eachTrademark->is_active,


                $eachTrademark->is_active,
                'deal_closed' => $eachTrademark->deal_closed,
            );
            if ($eachTrademark->deal_closed == 1 &&  $eachTrademark->buyer_id > 0) {
                $finalBuyer = Buyer::findOrFail($eachTrademark->buyer_id);
                $tempArr['finalBuyer'] =  "Name: " . $finalBuyer->name . ", Whatsapp: " . $finalBuyer->phone . ", Email: " . $finalBuyer->email . ",<br> Previous deals done: " . $finalBuyer->buyer_no_deal_closed . ", Amount of deals closed: " . number_format(($finalBuyer->buyer_amount_deal_closed) / 1000) . " Thousands";
            }
             $buyers = $eachTrademark->buyers;
            $tempArr['no_interested_buyer'] = 0;
            if (count($buyers) > 0) {
                // Count only buyers who have paid (is_active = 'active')
                $paidBuyersCount = 0;
                foreach ($buyers as $eachBuyer) {
                    if (!is_null($eachBuyer->pivot) && $eachBuyer->pivot->is_active == 'active') {
                        $paidBuyersCount++;
                    }
                }
                $tempArr['no_interested_buyer'] = $paidBuyersCount;
            }
            $buyersArr = array();
            foreach ($buyers as $eachBuyer) {
                // var_dump($eachBuyer);
                // $eachBuyer->pivot->is_active == 'active'
                if (!is_null($eachBuyer->pivot) && $eachBuyer->pivot->is_active == 'active'  ) {
                    $tempBuyer = array();
                    $tempBuyer['buyerDetail'] = "Name: " . $eachBuyer->name . ", Whatsapp: " . $eachBuyer->phone . ", Email: " . $eachBuyer->email . ",<br> Previous deals done: " . $eachBuyer->buyer_no_deal_closed . ", Amount of deals closed: " . number_format(($eachBuyer->buyer_amount_deal_closed) / 1000) . " Thousands";
                    $tempBuyer['buyer_id'] = $eachBuyer->pivot->buyer_id;
                    // array_push($buyers, $tempBuyer);
                    $buyersArr[] = $tempBuyer;
                }
            }
            $tempArr['buyers'] = $buyersArr;
            $arrTrademark[] = $tempArr;
        }
        return $arrTrademark;
    }
}
