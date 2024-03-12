<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthcoachClient extends Model
{
    use HasFactory;

    protected $fillable = ['hc_id','client_id'];
    
}
