<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiagnosticheadClient extends Model
{
    use HasFactory;
    protected $fillable = [
        'dh_id','client_id'
    ];

}
