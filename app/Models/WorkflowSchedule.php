<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_datettime','type','title','description','client_id','user_id','role','created_by','instruction','groupid','assignedto'
    ];
}
