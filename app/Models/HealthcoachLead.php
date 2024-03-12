<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthcoachLead extends Model
{
    use HasFactory;

    protected $fillable = ['healthcoach_id','lead_id'];
    
}
