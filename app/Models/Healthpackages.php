<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Healthpackages extends Model
{
    use HasFactory;

    protected $table = 'dh_healthpackages';

    protected $fillable = [
        'user_id',
        'name', 
        'num_of_test',
        'tests',
        'notes',
        'cost',
        'logo'
    ];

    protected $casts = [
        'tests' => 'array',
    ];
}
