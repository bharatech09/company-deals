<?php

namespace App\Models;
use App\Models\NocTrademark;
use App\Models\Company;
use App\Models\Property;
use App\Models\Assignment;

use Illuminate\Database\Eloquent\Model;

class Buyer extends Model
{
    protected $table = 'users';
    protected $fillable = [
        'name',
        'email',
        'email_verified',
        'phone',
        'phone_verified',
        'password',
        'buyer_no_deal_closed',
        'buyer_amount_deal_closed',
    ];
    public function properties()
    {
        return $this->belongsToMany(Property::class)->withPivot('id','is_active')->orderBy('updated_at', 'desc');
    }

    public function noctrademarks()
    {
        return $this->belongsToMany(NocTrademark::class)->withPivot('id','is_active')->orderBy('updated_at', 'desc');
    }

    public function assignments()
    {
        return $this->belongsToMany(Assignment::class)->withPivot('id','is_active')->orderBy('updated_at', 'desc');
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class)->withPivot('id','is_active')->orderBy('updated_at', 'desc');
    }


}
