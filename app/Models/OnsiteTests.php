<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnsiteTests extends Model
{
    use HasFactory;

    protected $table = 'dh_onsitetests';

    protected $fillable = [
        'user_id',
        'test_category', 
        'name',
        'part',
        'view',
        'cost',
        'notes',
        'logo',
        'status'
    ];
}
