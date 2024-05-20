<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoritesConsultants extends Model
{
    use HasFactory;

    protected $table = 'favorites_consultants';

    protected $fillable = [
        'favoroute_of',
        'consultant_ids'
    ];

    protected $casts = [
        'consultant_ids' => 'array',
    ];
}
