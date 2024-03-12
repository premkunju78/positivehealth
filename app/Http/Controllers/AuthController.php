<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
  public function authenticate(Request $request)
  {
    // $validation = Validator::make($request->all(), [
    //           'email' => 'required|email',
    //           'password' => 'required',
    //       ],[
    //         'email.required'=> 'Email field is required !'
    //       ]);
    //       if($validation->fails())
    //           return response()->json($validation->errors()->messages(),422);
    //
    //         $credentials = $request->only('email', 'password');
    //
    //         try {
    //             $user = \Auth::attempt($credentials);
    //             if (!$user) {
    //                 return response()->json(['message' => 'Invalid Credentials! Please try again.'], 422);
    //             }
    //         } catch ( \Exception $e) {
    //             return response()->json(['message' => 'This is something wrong. Please try again!'], 500);
    //         }
    //         $user = \App\Models\User::whereEmail(request('email'))->first();
    //
    //         $token = $user->createToken($user->email);
    //         return response()->json(['message' => 'You are successfully logged in!','user' => $user,'token' => $token]);

     //  if( user() ) {
     //    return response(['authenticated' => true]);
     // }
     //  return response(['authenticated' => false]);
  }


  public function user( Request $request )
  {
		$user =$request->user();
		
		if( $user->role_id > 3 ) 
		{
			$user->role_type = $user->detail->specialization;
			$user->is_upgraded = $user->detail->is_upgraded;
			if( in_array($user->role_type, ['']) && !$user->is_upgraded){
				$permissions = $user->role->permissions()->whereNotIN('name',config('removePermissions.consultant'))->pluck('name');
			}
		}
		else
		{
			$permissions = $user->role->permissions->pluck('name');
		}
		return response()->json([
			'user' => $user,
			'permissions' =>  $permissions ,
			'color' => $user->role->color,
		]);
  }
  
}
