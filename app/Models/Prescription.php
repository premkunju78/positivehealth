<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = ['active','data','client_id','user_id','active','instructions'];

    protected $casts = [
        'data' => 'array'
    ];
}
