<?php
namespace App\Models;
use App\Models\Property;
use App\Models\Company;
use App\Models\NocTrademark;
use App\Models\Assignment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    protected $guard = "user";
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified',
        'phone',
        'phone_verified',
        'password',
        'no_deal_closed',
        'amount_deal_closed',
        'buyer_no_deal_closed',
        'buyer_amount_deal_closed',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function companies()
    {
        return $this->hasMany(Company::class)->orderBy('updated_at', 'desc');
    }
    public function properties()
    {
        return $this->hasMany(Property::class)->orderBy('updated_at', 'desc');
    }

    
    public function nocTrademarks()
    {
        return $this->hasMany(NocTrademark::class)->orderBy('updated_at', 'desc');
    }
    public function assignments()
    {
        return $this->hasMany(Assignment::class)->orderBy('updated_at', 'desc');
    }
}
