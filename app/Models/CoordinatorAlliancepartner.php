<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoordinatorAlliancepartner extends Model
{
    use HasFactory;

    protected $fillable = ['coordinator_id', 'alliancepartner_id'];

    public function coordinator()
    {
        $this->belongsTo(User::class,'coordinator_id','id');
    }
    public function alliancepartner()
    {
        $this->belongsTo(User::class,'alliancepartner_id','id');
    }
}
