<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlianLeagueConsultant extends Model
{
    use HasFactory;

    protected $fillable = [
        'aliance_id','consultant_ids'
    ];
}
