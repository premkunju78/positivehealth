<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $casts = [
        'sessions' => 'array',
        'plans' => 'array',
        'cost' => 'array',
        'months' => 'array',
        'request_callback'=>'array'
    ];

    public function get_package() {
        return $this->hasOne(PackageUser::class, 'package_id', 'id');
    }
}
