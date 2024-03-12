<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketingheadAlliancepartner extends Model
{
    use HasFactory;

    protected $fillable = ['mh_id', 'alliancepartner_id'];

    public function coordinator()
    {
        $this->belongsTo(User::class,'mh_id','id');
    }
    public function alliancepartner()
    {
        $this->belongsTo(User::class,'alliancepartner_id','id');
    }
}
