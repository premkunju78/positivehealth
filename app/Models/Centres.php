<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Centres extends Model
{
    use HasFactory;

    protected $table = 'dh_centres';

    protected $fillable = [
        'user_id',
        'branch', 
        'full_address',
        'contact_number',
        'map_code',
        'available_test_category',
        'delivery_executive_number',
        'status'
    ];
}
