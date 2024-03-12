<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketingexecutiveAlliancepartner extends Model
{
    use HasFactory;

    protected $fillable = ['me_id', 'alliancepartner_id'];

    public function me()
    {
        $this->belongsTo(User::class,'me_id','id');
    }
    public function alliancepartner()
    {
        $this->belongsTo(User::class,'alliancepartner_id','id');
    }
}
