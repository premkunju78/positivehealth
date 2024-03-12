<?php

namespace App\Http\Controllers;

use App\Models\GroupUser;
use App\Models\PackageGroupMapping;
use App\Models\PackageGroupUser;
use App\Models\PackageUser;
use App\Models\PlanUser;
use App\Models\Questionnaire;
use App\Models\QuestionnaireUser;
use App\Models\ClientConsultant;
use App\Models\ClientGroupMapping;
use App\Models\DiagnosticheadClient;
use App\Models\RecipeUser;
use App\Models\UserVideo;
use App\Models\DiscountUser;
use App\Models\LeadProgram;
use Illuminate\Http\Request;
use DB;

class AssignmentController extends Controller
{
  public function store(Request $request)
  {
    $request->validate($this->rules());
    if (count($request->users)) {
      $users = $request->users;
    } else if (count($request->clients)) {
      $users = $request->clients;
    } else {
      $users = $request->partners;
    }
    foreach ($users as $user) {
      $data[] = [
        'user_id' => $user,
        'video_id' => $request->video,
        'expiry_date' => $request->expiry_date,
        'assigned_date' => date('Y-m-d'),
      ];
    }
    UserVideo::insert($data);
    return response(['success' => true, 'message' => 'video assigned successfully']);
  }
  public function rules()
  {
    return [
      'role' => 'required',
    ];
  }
  public function users($id)
  {
    $users = UserVideo::join('users', 'users.id', '=', 'user_videos.user_id')
      ->join('roles', 'roles.id', '=', 'users.role_id')
      ->where('users.id', '!=', auth()->user()->id)
      ->select('users.*', 'roles.name as role', 'user_videos.id as user_video_id', 'user_videos.expiry_date')->where('user_videos.video_id', $id)->get();
    $totalRows = $users->count();
    return response()->json([
      'users' => $users,
      'totalRows' => $totalRows,
    ]);
  }
  public function groupUsers($id)
  {
    $users = GroupUser::join('users', 'users.id', '=', 'group_users.user_id')
      ->where('users.id', '!=', auth()->user()->id)
      ->join('roles', 'roles.id', '=', 'users.role_id')
      ->select('users.*', 'roles.name as role', 'group_users.id as group_user_id')->where('group_users.group_id', $id)->get();
    $totalRows = $users->count();
    return response()->json([
      'users' => $users,
      'totalRows' => $totalRows,
    ]);
  }
  public function storeGroupAssignment(Request $request)
  {
    $request->validate($this->rules());
    if (count($request->users)) {
      $users = $request->users;
    } else if (count($request->clients)) {
      $users = $request->clients;
    } else {
      $users = $request->partners;
    }
    foreach ($users as $user) {
      $data[] = [
        'user_id' => $user,
        'group_id' => $request->group,
        'expiry_date' => $request->expiry_date
      ];
    }
    GroupUser::insert($data);
    return response(['success' => true, 'message' => 'group assigned successfully']);
  }
  public function storeUserVideoAssignment(Request $request)
  {
    $request->validate(
      ['client_id' => 'required', 'assignedVideos' => 'required'],
      ['client_id.required' => 'please select at least one video']
    );
    foreach ($request->assignedVideos as $video) {
      $data[] = [
        'user_id' => $request->client_id,
        'video_id' => $video,
        'expiry_date' => $request->expiry_date
      ];
    }
    UserVideo::insert($data);
    return response(['success' => true, 'message' => 'video assigned successfully']);
  }
  public function storeDiscountCouponAssignment(Request $request)
  {
    foreach ($request->coordinators as $coordinator) {
      $data[] = [
        'user_id' => $coordinator,
        'coupon_id' => $request->coupon_id,
      ];
    }
    DiscountUser::updateOrCreate([
      'user_id' => $coordinator,
      'coupon_id' => $request->coupon_id,
    ], $data);
    return response(['success' => true, 'message' => 'coupon assigned successfully']);
  }
  public function destroy(int $id)
  {
    $type = request('type');
    if ($type === 'video') {
      UserVideo::find($id)->delete();
    } else if ($type === 'questionnaire') {
      QuestionnaireUser::find($id)->delete();
    } else if ($type === 'package') {
      // $package = PackageUser::find($id);
      // $users = \DB::table('alian_client')->where('aliance_id', $package->user_id)->pluck('client_id')->toArray();
      // PackageUser::whereIn('user_id', $users)->delete();
      PackageUser::find($id)->delete();
    } else if ($type === 'packageGroup') {
      PackageGroupUser::find($id)->delete();
    } else if ($type === 'dietPlan') {
      PlanUser::find($id)->delete();
    } else if ($type === 'recipe') {
      RecipeUser::find($id)->delete();
    } else if ($type === 'client') {
      ClientConsultant::find($id)->delete();
    } else if ($type === 'clientGroups') {
      ClientGroupMapping::find($id)->delete();
    } else if ($type === 'dh') {
      DiagnosticheadClient::find($id)->delete();
    } else {
      GroupUser::find($id)->delete();
    }
    return response(['success' => true, 'message' => 'user unassigned successfully']);
  }
  public function removeVideo($video_id, $user_id)
  {
    $userVideo = UserVideo::where([
      'video_id' => $video_id,
      'user_id' => $user_id
    ])->first();
    if ($userVideo) {
      $userVideo->delete();
      return response(['success' => true, 'message' => 'video deleted successfully']);
    } else {
      return response(['success' => false, 'message' => 'video not found']);
    }
  }
  public function storeUserQuestionnaireAssignment(Request $request)
  {
    $request->validate(
      ['clients.*' => 'required', 'questionnaire' => 'required']
    );
    foreach ($request->clients as $user) {
      // $data[] = [
      //   'questionnaire_id' => $request->questionnaire,
      //   'user_id' => $user
      // ];
      QuestionnaireUser::updateOrCreate([
        'questionnaire_id' => $request->questionnaire,
        'user_id' => $user
      ], [
        'questionnaire_id' => $request->questionnaire,
        'user_id' => $user
      ]);
    }
    // QuestionnaireUser::insert($data);
    return response(['success' => true, 'message' => 'Questionnaire assigned successfully']);
  }
  public function removeAssignedQuestionnaireUser($qoestionnaire_id, $user_id)
  {
    $qoestionnaireUser = QuestionnaireUser::where([
      'qoestionnaire_id' => $qoestionnaire_id,
      'user_id' => $user_id
    ])->first();
    if ($qoestionnaireUser) {
      $qoestionnaireUser->delete();
      return response(['success' => true, 'message' => 'user removed successfully']);
    } else {
      return response(['success' => false, 'message' => 'data not found']);
    }
  }
  public function questionnaireUsers(int $id)
  {
    $users = QuestionnaireUser::join('users', 'users.id', '=', 'questionnaire_users.user_id')
      ->join('roles', 'roles.id', '=', 'users.role_id')
      ->where('users.id', '!=', auth()->user()->id)
      ->select('users.*', 'roles.name as role', 'questionnaire_users.id as questionnaire_user_id', 'questionnaire_users.result')
      ->where('questionnaire_users.questionnaire_id', $id)->get();
    $totalRows = $users->count();
    return response()->json([
      'users' => $users,
      'totalRows' => $totalRows,
    ]);
  }
  public function storeUserPackageAssignment(Request $request)
  {
    $request->validate(
      ['partners.*' => 'required', 'package' => 'required', 'cost' => 'sometimes|nullable']
    );
    foreach ($request->partners as $user) {
      $data[] = [
        'package_id' => $request->package,
        'user_id' => $user,
        'cost' => 0,
        'data' => json_encode($request->cost) ?? [],
        'expiry_date' => $request->expiry_date
      ];
      $users = DB::table('alian_client')->where('aliance_id', $user)->pluck('client_id')->toArray();
      if (count($users)) {
        foreach ($users as $client) {
          $data[] = [
            'package_id' => $request->package,
            'user_id' => $client,
            'cost' =>  0,
            'data' => json_encode($request->cost) ?? [],
            'expiry_date' => $request->expiry_date
          ];
        }
      }
    }
    PackageUser::insert($data);
    return response(['success' => true, 'message' => 'program assigned successfully']);
  }

  public function storeCustomUserPackageAssignment(Request $request)
  {
    $request->validate(
      ['package' => 'required', 'cost' => 'sometimes|nullable']
    );
    $data = array($request->plan => $request->cost);
    foreach ($request->clients as $user) {
      PackageUser::updateOrCreate(['package_id' => $request->package, 'user_id' => $user], ['cost' => 0, 'expiry_date' => null, 'data' => $data]);
    }
    foreach ($request->leads as $lead) {
      LeadProgram::updateOrCreate(['program_id' => $request->package, 'lead_id' => $lead], ['plan' => $request->plan, 'price' => $request->cost]);
    }
    return response(['success' => true, 'message' => 'program assigned successfully']);
  }

  public function PackageUsers(int $id)
  {
    $users = PackageUser::join('users', 'users.id', '=', 'package_users.user_id')
      ->join('roles', 'roles.id', '=', 'users.role_id')
      ->where('users.id', '!=', auth()->user()->id)
      ->where('users.role_id', 2)
      ->select(
        'users.*',
        'roles.name as role',
        'package_users.id as package_user_id',
        'package_users.expiry_date',
        'package_users.cost',
        'package_users.transaction_id',
      )
      ->where('package_users.package_id', $id)->get();
    $totalRows = $users->count();
    return response()->json([
      'users' => $users,
      'totalRows' => $totalRows,
    ]);
  }
  public function storeUserPackageGroupAssignment(Request $request)
  {
    $request->validate(
      ['partners.*' => 'required', 'group' => 'required', 'cost.*' => 'required']
    );
    DB::beginTransaction();
    try {
      foreach ($request->partners as $user) {
        PackageGroupUser::updateOrCreate([
          'group_id' => $request->group,
          'user_id' => $user,
        ], [
          'group_id' => $request->group,
          'user_id' => $user,
          'cost' => \DB::raw('cost +' . array_sum($request->cost)),
          'expiry_date' => $request->expiry_date
        ]);
        foreach ($request->cost as $package => $cost) {
          PackageUser::updateOrCreate(
            [
              'package_id' => $package,
              'user_id' => $user,
            ],
            [
              'package_id' => $package,
              'user_id' => $user,
              'cost' => $cost,
              'expiry_date' => $request->expiry_date
            ]
          );
          $users = DB::table('alian_client')->where('aliance_id', $user)->pluck('client_id')->toArray();
          if (count($users)) {
            foreach ($users as $client) {
              PackageUser::updateOrCreate(
                [
                  'package_id' => $package,
                  'user_id' => $client,
                ],
                [
                  'package_id' => $package,
                  'user_id' => $client,
                  'cost' => $cost,
                  'expiry_date' => $request->expiry_date
                ]
              );
            }
          }
        }
      }
      DB::commit();
      return response(['success' => true, 'message' => 'program assigned successfully']);
    } catch (\Exception $e) {
      DB::rollback();
      return response(['success' => false, 'message' => 'something went wrong, Please try after some time']);
    }
  }
  public function PackageGroupUsers(int $id)
  {
    $users = PackageGroupUser::join('users', 'users.id', '=', 'package_group_users.user_id')
      ->join('roles', 'roles.id', '=', 'users.role_id')
      ->where('users.id', '!=', auth()->user()->id)
      ->select(
        'users.*',
        'roles.name as role',
        'package_group_users.id as package_group_user_id',
        'package_group_users.expiry_date',
        'package_group_users.cost'
      )
      ->where('package_group_users.group_id', $id)->get();
    $totalRows = $users->count();
    return response()->json([
      'users' => $users,
      'totalRows' => $totalRows,
    ]);
  }
  public function groupPackages(int $id)
  {
    $packages = PackageGroupMapping::join('packages', 'packages.id', '=', 'package_group_mappings.package_id')
      ->select('packages.*', 'package_group_mappings.id as package_group_id')
      ->when(request('assign'), function ($q) {
        $q->whereNotIn('packages.id', PackageUser::whereIn('user_id', request('users') ?? [])->pluck('package_id')->toArray());
      })
      ->where('package_group_mappings.group_id', $id)->get();
    $totalRows = $packages->count();
    return response()->json([
      'packages' => $packages,
      'totalRows' => $totalRows,
    ]);
  }
  public function dietPlanUsers(int $id)
  {
    $users = PlanUser::join('users', 'users.id', '=', 'plan_users.user_id')
      ->join('roles', 'roles.id', '=', 'users.role_id')
      ->where('users.id', '!=', auth()->user()->id)
      ->select(
        'users.*',
        'roles.name as role',
        'plan_users.id as plan_user_id',
        'plan_users.expiry_date',
      )
      ->where('plan_users.diet_plan_id', $id)->get();
    $totalRows = $users->count();
    return response()->json([
      'users' => $users,
      'totalRows' => $totalRows,
    ]);
  }
  public function storeUserPlanAssignment(Request $request)
  {
    $request->validate(
      ['clients.*' => 'required', 'plan' => 'required', 'expiry_date' => 'nullable']
    );
    DB::beginTransaction();
    try {
      foreach ($request->clients as $user) {
        PlanUser::updateOrCreate([
          'diet_plan_id' => $request->plan,
          'user_id' => $user,
        ], [
          'diet_plan_id' => $request->plan,
          'user_id' => $user,
          'expiry_date' => $request->expiry_date
        ]);
      }
      DB::commit();
      return response(['success' => true, 'message' => 'diet plan assigned successfully']);
    } catch (\Exception $e) {
      DB::rollback();
      dd($e);
      return response(['success' => false, 'message' => 'something went wrong, Please try after some time']);
    }
  }
  public function recipeUsers(int $id)
  {
    $users = RecipeUser::join('users', 'users.id', '=', 'recipe_users.user_id')
      ->join('roles', 'roles.id', '=', 'users.role_id')
      ->where('users.id', '!=', auth()->user()->id)
      ->select(
        'users.*',
        'roles.name as role',
        'recipe_users.id as recipe_user_id',
      )
      ->where('recipe_users.recipe_id', $id)->get();
    $totalRows = $users->count();
    return response()->json([
      'users' => $users,
      'totalRows' => $totalRows,
    ]);
  }
  public function storeUserRecipeAssignment(Request $request)
  {
    $request->validate(
      ['clients.*' => 'required', 'recipe' => 'required']
    );
    DB::beginTransaction();
    try {
      foreach ($request->clients as $user) {
        RecipeUser::updateOrCreate([
          'recipe_id' => $request->recipe,
          'user_id' => $user,
        ], [
          'recipe_id' => $request->recipe,
          'user_id' => $user,
        ]);
      }
      DB::commit();
      return response(['success' => true, 'message' => 'recipe assigned successfully']);
    } catch (\Exception $e) {
      DB::rollback();
      return response(['success' => false, 'message' => 'something went wrong, Please try after some time']);
    }
  }
  public function storeUserClientAssignment(Request $request)
  {
    $request->validate(
      ['clients.*' => 'required', 'client' => 'required']
    );
    DB::beginTransaction();
    try {
      foreach ($request->consultants as $user) {
        $data[] = [
          'client_id' => $request->client,
          'consultant_id' => $user
        ];
      }
      ClientConsultant::insert($data);
      DB::commit();
      return response(['success' => true, 'message' => 'Consultant assigned successfully']);
    } catch (\Exception $e) {
      DB::rollback();
      dd($e);
      return response(['success' => false, 'message' => 'something went wrong, Please try after some time']);
    }
  }
  public function clientConsultants(int $id)
  {
    $users = ClientConsultant::join('users', 'users.id', '=', 'client_consultants.consultant_id')
      ->join('roles', 'roles.id', '=', 'users.role_id')
      ->where('users.id', '!=', auth()->user()->id)
      ->select(
        'users.*',
        'roles.name as role',
        'client_consultants.id as client_consultant_id',
      )
      ->where('client_consultants.client_id', $id)->get();
    $totalRows = $users->count();
    return response()->json([
      'users' => $users,
      'totalRows' => $totalRows,
    ]);
  }
}
