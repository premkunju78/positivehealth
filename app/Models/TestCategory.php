<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'active'];

    public function test()
    {
        return $this->hasMany(Test::class);
    }
}
