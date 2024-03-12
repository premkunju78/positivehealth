<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientReferral extends Model
{
    use HasFactory;

    protected $fillable = [
        'referred_by','referred_to','client_id','notes','date'
    ];

    public $timestamps = false;
}
