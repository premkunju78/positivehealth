<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'group_name',
        'group_type',
        'session_type',
        'session_no',
        'no_of_participants',
        'payment_amount',
        'description',
        'logo',
        'sessions',
        'meta'
    ];

    protected $casts = [
        'sessions' => 'array',
    ];
}
