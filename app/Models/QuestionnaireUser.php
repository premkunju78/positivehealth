<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionnaireUser extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','questionnaire_id','result'];

    protected $casts = [
        'result' => 'array'
    ];

    public function questionnaire()
    {
        return $this->belongsToMany(Questionnaire::class);
    }
}
