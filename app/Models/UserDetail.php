<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'address', 'city', 'pincode', 'user_id', 'logo', 'signature_img', 'banner_img',
        'dob', 'gender', 'occupation', 'guard_mob', 'state', 'company_name', 'contact_person',
        'company_contact', 'website_name', 'url', 'group', 'type', 'welcome_message',
        'cost', 'cost_percent', 'percentage', 'specialization', 'authorization', 'specialized_in',
        'qualification', 'certificates', 'bank_name', 'account_no', 'ifsc_code', 'league', 'incharge',
        'cancelled_cheque', 'bio', 'notes', 'clinic_name', 'aim', 'experience', 'skills', 'languages', 'cisf', 'wecome_message', 'idproof','marital_status','wedding_date','terms_condition','service_areas','available_cities'
    ];

    protected $casts = [
        'certificates' => 'array',
        'skills' => 'array',
        'languages' => 'array',
        'service_areas' => 'array',
        'available_cities' => 'array'
    ];
}
