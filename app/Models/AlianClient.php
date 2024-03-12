<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlianClient extends Model
{
    use HasFactory;

    protected $table = 'alian_client';

    protected $fillable = [
        'aliance_id', 'client_id'
    ];
}
