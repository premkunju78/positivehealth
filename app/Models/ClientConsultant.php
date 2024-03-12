<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientConsultant extends Model
{
    use HasFactory;

    protected $fillable = ['consultant_id','client_id','notes','referred'];
    
    protected $casts = [
        'notes' => 'array'
    ];
}
