<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id','program_id','plan','price'
    ];
}
