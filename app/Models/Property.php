<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Kyslik\ColumnSortable\Sortable;

class Property extends Model
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
        'space',
        'type',
        'ask_price',
        'ask_price_unit',
        'ask_price_amount',
        'deal_closed',
        'buyer_id',
        'address',
        'pincode',
        'state',
        'status',
        'payment_id',
        'home_featured',
        'approved'
    ];

    /**
     * Sortable fields for column-sortable
     */
    public $sortable = [
        'urn',
        'space',
        'type',
        'ask_price',
        'status',
        'approved',
        'created_at',
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
        return self::join('payments', 'properties.id', '=', 'payments.service_id')
            ->where('service_id', $this->id)
            ->where('service_type', 'seller_property')
            ->select('payments.*')
            ->orderBy('updated_at', 'desc')
            ->get();
    }

    public static function seller_properties($condition, $third = false)
    {
        $user = \Auth::guard('user')->user();

        if ($condition == "all") {
            $properties = $user->properties;
        } else {
            $properties = $user->properties()
                ->where(function ($q) {
                    $q->where('properties.deal_closed', 0)
                      ->orWhereNull('properties.deal_closed');
                })
                ->where('properties.status', $condition)
                ->get();
        }

        if ($third === true) {
            $properties = $user->properties()->where('properties.deal_closed', 1)->get();
        }

        $arrPrperty = [];

        foreach ($properties as $eachProperty) {
            $tempArr = [
                'id' => $eachProperty->id,
                'urn' => $eachProperty->urn,
                'state' => $eachProperty->state,
                'pincode' => $eachProperty->pincode,
                'address' => $eachProperty->address,
                'space' => $eachProperty->space,
                'type' => $eachProperty->type,
                'ask_price' => $eachProperty->ask_price,
                'ask_price_unit' => $eachProperty->ask_price_unit,
                'status' => ($eachProperty->deal_closed) ? "Deal Closed" : $eachProperty->status,
                'deal_closed' => $eachProperty->deal_closed,
                'buyer_id' => $eachProperty->buyer_id,
                'approved' => $eachProperty->approved,
                'payment_id' => $eachProperty->payment_id,
            ];

            if ($eachProperty->deal_closed == 1 && $eachProperty->buyer_id > 0) {
                $finalBuyer = Buyer::findOrFail($eachProperty->buyer_id);
                $tempArr['finalBuyer'] = "Name: {$finalBuyer->name}, Whatsapp: {$finalBuyer->phone}, Email: {$finalBuyer->email},<br> Previous deals done: {$finalBuyer->buyer_no_deal_closed}, Amount of deals closed: " . number_format($finalBuyer->buyer_amount_deal_closed / 1000) . " Thousands";
            }

            $buyers = $eachProperty->buyers;
            $tempArr['no_interested_buyer'] = count($buyers);
            $buyersArr = [];

            foreach ($buyers as $eachBuyer) {
                if (!is_null($eachBuyer->pivot) && $eachBuyer->pivot->is_active === 'active') {
                    $buyersArr[] = [
                        'buyerDetail' => "Name: {$eachBuyer->name}, Whatsapp: {$eachBuyer->phone}, Email: {$eachBuyer->email},<br> Previous deals done: {$eachBuyer->buyer_no_deal_closed}, Amount of deals closed: " . number_format($eachBuyer->buyer_amount_deal_closed / 1000) . " Thousands",
                        'buyer_id' => $eachBuyer->pivot->buyer_id,
                    ];
                }
            }

            $tempArr['buyers'] = $buyersArr;
            $arrPrperty[] = $tempArr;
        }

        return $arrPrperty;
    }
}
