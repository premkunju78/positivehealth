<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlliancePartner extends Model
{
    use HasFactory;

    public static function tableData()
    {
        return self::join('users','users.id','=','alliance_partners.user_id')
        ->join('roles','roles.id','=','users.role_id')->where('users.role_id',2);
    }


}
