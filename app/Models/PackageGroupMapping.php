<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageGroupMapping extends Model
{
    use HasFactory;

    protected $fillable = ['package_id','group_id'];
}
