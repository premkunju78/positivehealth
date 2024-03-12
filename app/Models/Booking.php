<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','description','user_id','booked_by','timeslot_from','timeslot_to','utc_timeslot_to',
        'razorpay_payment_id','razorpay_order_id','razorpay_signature','cost','transaction_date'
    ];
}
