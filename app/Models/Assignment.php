<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Assignment extends Model
{
        use Sortable;

    public $sortable = [
        'urn', 'category', 'subject', 'deal_price', 'is_active', 'approved', 'created_at'
    ];

    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'urn',
        'user_id',
        'category',
        'subject',
        'description',
        'deal_price',
        'deal_price_unit',
        'deal_price_amount',
        'payment_id',
        'is_active',
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
        $payments = self::join('payments', 'assignments.id', '=', 'payments.service_id')->where('service_id', '=', $this->id)->where('service_type', '=', 'seller_assignment')->select('payments.*')->orderBy('created_at', 'desc')->get();
        return $payments;
    }
    public static function buyer_assignments()
    {
        $buyer_id =  \Auth::guard('user')->id();
        $buyer = Buyer::findOrFail($buyer_id);
        $interestedAssignments = $buyer->assignments;
        $interestedAssignmentArr = array();
        foreach ($interestedAssignments as $key => $assignment) {
            if ($assignment->deal_closed == 1)
                continue;
            $tempAssignment = self::getAssignmentDetail($assignment);
            $tempAssignment['buyer_status'] = $assignment->pivot->is_active;
            if ($assignment->pivot->is_active == 'active') {
                $seller_id = $assignment->user_id;
                $seller = User::where('id', $seller_id)->first();
                //  var_dump($seller);
                $tempAssignment['seller'] = "Name: " . $seller->name . ", Phone: " . $seller->phone . ", Email: " . $seller->email;
                if ($seller->amount_deal_closed > 0) {
                    $tempAssignment['seller'] .= "<br>No of deal closed: " . $seller->no_deal_closed . ", amount of deal closed: " . number_format($seller->amount_deal_closed);
                }
            }
            $interestedAssignmentArr[] = $tempAssignment;
            return $interestedAssignmentArr;
        }
    }
    protected static function getAssignmentDetail($assignment)
    {
        return array(
            'id' => $assignment->id,
            'urn' => $assignment->urn,
            'category' => $assignment->category,
            'subject' => $assignment->subject,
            'description' => $assignment->description,
            'deal_price' => $assignment->deal_price,
            'is_active' => $assignment->is_active,
            'deal_closed' => $assignment->deal_closed
        );
    }
    public static function seller_assignments($condition, $third = false)
    {
        $user =  \Auth::guard('user')->user();

        if ($condition == "all") {
            $assignments = $user->assignments;
        } else {
            $assignments = $user->assignments()->where(function ($q) {
                $q->where('assignments.deal_closed', 0)
                    ->orWhereNull('assignments.deal_closed');   // 0 या NULL दोनों ऐक्सेप्ट
            })->where('assignments.is_active', $condition)->get();
        }

        if ($third == true) {
            $assignments = $user->assignments()->where('assignments.deal_closed', 1)->get();
        }
        $arrAssignment = array();
        foreach ($assignments as $eachAssignment) {
            $tempArr = array(
                'id' => $eachAssignment->id,
                'urn' => $eachAssignment->urn,
                'category' => $eachAssignment->category,
                'subject' => $eachAssignment->subject,
                'description' => $eachAssignment->description,
                'is_active' => $eachAssignment->is_active,
                'deal_price' => $eachAssignment->deal_price,
                'deal_price_unit' => $eachAssignment->deal_price_unit,
                'deal_closed' => $eachAssignment->deal_closed,
                'approved' => $eachAssignment->approved,
                'payment_id' => $eachAssignment->payment_id,
                'buyer_id' => $eachAssignment->buyer_id,
                'is_active' => ($eachAssignment->deal_closed) ? "Deal Closed" : $eachAssignment->is_active,
            );

            if ($eachAssignment->deal_closed == 1 &&  $eachAssignment->buyer_id > 0) {
                $finalBuyer = Buyer::findOrFail($eachAssignment->buyer_id);
                $tempArr['finalBuyer'] =  "Name: " . $finalBuyer->name . ", Whatsapp: " . $finalBuyer->phone . ", Email: " . $finalBuyer->email . ",<br> Previous deals done: " . $finalBuyer->buyer_no_deal_closed . ", Amount of deals closed: " . number_format(($finalBuyer->buyer_amount_deal_closed) / 1000) . " Thousands";
            }

            $buyers = $eachAssignment->buyers;
            $tempArr['no_interested_buyer'] = 0;
            if (count($buyers) > 0) {
                $tempArr['no_interested_buyer'] = count($buyers);
            }

            $buyersArr = array();
            foreach ($buyers as $eachBuyer) {
                // var_dump($eachBuyer);
                if (!is_null($eachBuyer->pivot)  && $eachBuyer->pivot->is_active == 'active') {
                    $tempBuyer = array();
                    $tempBuyer['buyerDetail'] = "Name: " . $eachBuyer->name . ", Whatsapp: " . $eachBuyer->phone . ", Email: " . $eachBuyer->email . ",<br> Previous deals done: " . $eachBuyer->buyer_no_deal_closed . ", Amount of deals closed: " . number_format(($eachBuyer->buyer_amount_deal_closed) / 1000) . " Thousands";
                    $tempBuyer['buyer_id'] = $eachBuyer->pivot->buyer_id;
                    $buyersArr[] = $tempBuyer;
                }
            }
            $tempArr['buyers'] = $buyersArr;
            $arrAssignment[] = $tempArr;
        }
        return $arrAssignment;
    }
}
