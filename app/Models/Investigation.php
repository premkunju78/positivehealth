<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investigation extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id','test','user_id','active','files', 'meta'
    ];

    protected $casts = [
        'test' => 'array',
        'files' => 'array',
        'meta' => 'array',
    ];

}
