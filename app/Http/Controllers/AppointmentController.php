<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use DB;
use App\Models\Role;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use stdClass;
use App\Models\User;

class AppointmentController extends Controller
{
  public function index()
  {
    // $availability = Availability::whereDate('start', '>=', date('Y-m-d'))
    //                 ->where('user_id', auth()->user()->id)->get();
    //
    // return response()->json($availability);
  }

  public function get_roles(Request $request)
  {
    $data = DB::table('roles')->select('id', 'name', 'label', 'description')->get();
    return response()->json([
      'status' => 'success',
      'data' => $data
    ]);
  }
  public function get_role_users(Request $request, $id = null)
  {
    // User Data
    $data = User::join('roles', 'roles.id', '=', 'users.role_id')
      ->join('user_details', 'user_details.user_id', '=', 'users.id')
      ->where('user_details.authorization', '=', 'External')
      ->where('users.role_id', $id)
      ->select(
        'users.id',
        'users.first_name',
        'users.last_name',
        'users.email',
        'users.status',
        'users.avatar',
        'users.phone',
        'user_details.*'
      )
      ->get();
    $userData = [];
    foreach ($data as $value) {
      $appointData = DB::table('availabilities')->where('user_id', $value->user_id)->select('start', 'end', 'frequency')->get();
      $appoint = new stdClass();
      $appoint->user_data = $value;
      $appoint->availability_data = $appointData;
      array_push($userData, $appoint);
    }

    $total = count($userData);
    $per_page = 5;
    $current_page = $request->input("page") ?? 1;
    $starting_point = ($current_page * $per_page) - $per_page;
    $array = array_slice($userData, $starting_point, $per_page, true);

    $data_wit_pagination = new Paginator($array, $total, $per_page, $current_page, [
      'path'  => $request->url(),
      'query' => $request->query(),
    ]);
    //$data = DB::table('migrations')->paginate(5);
    return response()->json([
      'status' => 'success',
      'response' => $data_wit_pagination,
    ]);
  }

  public function get_external_consultants(Request $request)
  {
    // User Data
    $data = User::join('roles', 'roles.id', '=', 'users.role_id')
      ->join('user_details', 'user_details.user_id', '=', 'users.id')
      ->where('user_details.authorization', '=', 'External')
      ->select(
        'users.id',
        'users.first_name',
        'users.last_name',
        'users.email',
        'users.status',
        'users.avatar',
        'users.phone',
        'user_details.*'
      )
      ->get();
    $userData = [];
    foreach ($data as $value) {
      $appointData = DB::table('availabilities')->where('user_id', $value->user_id)->select('start', 'end', 'frequency')->get();
      $appoint = new stdClass();
      $appoint->user_data = $value;
      $appoint->availability_data = $appointData;
      array_push($userData, $appoint);
    }

    $total = count($userData);
    $per_page = 5;
    $current_page = $request->input("page") ?? 1;
    $starting_point = ($current_page * $per_page) - $per_page;
    $array = array_slice($userData, $starting_point, $per_page, true);

    $data_wit_pagination = new Paginator($array, $total, $per_page, $current_page, [
      'path'  => $request->url(),
      'query' => $request->query(),
    ]);
    //$data = DB::table('migrations')->paginate(5);
    return response()->json([
      'status' => 'success',
      'response' => $data_wit_pagination,
    ]);
  }
}
