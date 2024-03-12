<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageGroupUser extends Model
{
    use HasFactory;

    protected $fillable = ['group_id','user_id','cost','expiry_date'];


}
