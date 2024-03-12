<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\utils\helpers;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
  //----------- GET ALL Roles --------------\\

  public function index(Request $request)
  {
      $helpers = new helpers();
      $roles = Role::where('deleted_at', '=', null)->get();
      $totalRows = $roles->count();
      return response()->json([
          'roles' => $roles,
          'totalRows' => $totalRows,
      ]);
  }

  public function list(){
    return response()->json( [
      'permissions' => Permission::pluck('name','id')->toArray(),
      'modules' => config('modules')
    ]);
  }

}
