<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiagnosticheadAlliance extends Model
{
    use HasFactory;
    protected $table = 'diagnostichead_alliance';

    protected $fillable = [
        'dh_id','ap_id'
    ];

}
