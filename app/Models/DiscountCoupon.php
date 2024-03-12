<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCoupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'discount_type','discount_code','applies_to',
        'discount_value','start_date','end_date','user_id'
    ];

    public function events()
    {
        return $this->belongsToMany(
            EventTypes::class,
            'event_coupons',
            'discount_coupon_id',
            'event_type_id'
        );
    }

    public function event_types()
    {
        return $this->events()->select('event_coupons.event_type_id as id');
    }
}
