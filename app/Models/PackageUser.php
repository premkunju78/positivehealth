<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageUser extends Model
{
    use HasFactory;

    protected $fillable = ['package_id','user_id','cost','expiry_date','transaction_id','transaction_date','data'];

    protected $casts = [
        'meta' => 'array',
        'data' => 'array'
    ];
}
