<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestigationTests extends Model
{
    use HasFactory;

    protected $table = 'dh_investigation_tests';

    protected $fillable = [
        'user_id',
        'category_id', 
        'sub_category_id',
        'type',
        'cost',
        'status'
    ];
}
