<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    use HasFactory;

    protected $fillable = [ 'name', 'description',' active', 'content' ];

    protected $casts = [
        'content' => 'array'
    ];

    public function users()
    {
        return $this->belongsToMany(
             User::class,
            'questionnaire_users',
            'questionnaire_id',
            'user_id'
        );
    }
}
