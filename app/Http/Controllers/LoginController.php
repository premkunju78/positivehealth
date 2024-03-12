<?php

namespace App\Http\Controllers;

use App\Models\AlianClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserDetail;
use Validator;

class LoginController extends Controller
{
  public function login(Request $request)
  {

    $validation = Validator::make($request->all(), [
      'email' => 'required',
      'password' => 'required',
    ], [
      'email.required' => 'email/phone field is required'
    ]);

    if ($validation->fails()) {
      return response()->json($validation->errors()->messages(), 422);
    }

    $conditions = [];

    if (is_numeric($request->email)) {
      $conditions =  ['phone' => $request->email];
    } elseif (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
      $conditions = ['email' => $request->email];
    } else {
      return response()->json([
        'email_msg' => ['Please provide a valid email/ phone number']
      ], 422);
    }


    $user = User::where($conditions)->first();
    if ($user->approve_status == '2') {
      return response()->json([
        'error_msg' => ['Your account is not approved yet. Please wait for approval or contact admin.']
      ], 422);
    }

    if (!empty($request->url) && $user->role_id != 2) {
      return response()->json([
        'error_msg' => ['Please login with valid url.']
      ], 422);
    }

    if (!$user || !Hash::check($request->password, $user->password)) {

      return response()->json([
        'error_msg' => ['The provided credentials are incorrect.']
      ], 422);
    }

    if ($user->role_id === 2 || $user->role_id  > 3) {
      $user->logo = (!empty($user->detail->logo)) ? $user->detail->logo : '';
    }

    $user->company_name = $user->role_id === 2 ? $user->detail->company_name : $user->name;


    $token = $user->createToken($request->device_name)->plainTextToken;

    return response()->json(['user' => $user, 'token' => $token,   'permissions' => $this->getPermissions($user, $request)]);
  }

  public function logout()
  {
    $user = auth()->guard('sanctum')->user();
    $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
    return response()->json(['success' => true, 'user', $user]);
  }

  private function getPermissions(&$user, Request $request)
  {
    $user->is_upgraded = $user->role_id === 1 ? 1 : 0;
    $user->role_type = null;

    if ($user->role_id > 3) {
      $detail = $user->detail()->select('is_upgraded', 'specialization as role_type', 'user_id')->first();
      $user->role_type = $detail->role_type;
      $user->is_upgraded = $detail->is_upgraded;
      if (in_array($user->role_type, ['Medical', 'Wellness Provider']) && !$user->is_upgraded) {
        return  $user->role->permissions()->whereNotIN('name', config('removePermissions.consultant'))->pluck('name');
      }
    }
    return $user->role->permissions->pluck('name');
  }

  public function user(Request $request)
  {
    $user = $request->user();
    //$user->dob = date('d-m-Y', strtotime($user->detail->dob));
    if(isset($user->detail)){
      $user->hide_consultants = $user->detail->hide_consultants;
    }
    
    if ($user->role_id === 2 || $user->role_id  > 3) {
      $user->logo = $user->detail->logo;
    }
    if ($user->role_id === 3) {
      $logo = UserDetail::where('user_id', AlianClient::where('client_id', $user->id)->value('aliance_id'))->value('logo');
      $user->logo = $logo;
    }
    $user->company_name = $user->role_id === 2 ? $user->detail->company_name : $user->name;
    return response()->json([
      'user' => $user,
      'permissions' =>  $this->getPermissions($user, $request),
      'color' => $user->role->color,
    ]);
  }
}
