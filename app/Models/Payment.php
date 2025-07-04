<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'service_id',
        'amount',
        'status',
        'transaction_id',
        'service_start_date',
        'service_end_date',
        'payment_method',
        'transaction_id',
        'payment_from',
        'payment_type',
        'notes',
    ];
    public static function getAllPayment($service_id,$service_type){
        $filterCon = array();
        $filterCon[] = array('service_id','=',$service_id);
        $filterCon[] = array('service_type','=',$service_type);
        $payments = self::where($filterCon)->get();
        return $payments;

    }
}