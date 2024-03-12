<?php

namespace App\Http\Controllers;

use App\Models\AlianClient;
use App\Models\Availability;
use App\Models\Booking;
use App\Models\ClientConsultant;
use App\Models\ClientReferral;
use App\Models\GroupUser;
use App\Models\Notification;
use App\Models\User;
use App\Models\RoleUser;
use App\Models\UserDetail;
use App\Models\UserVideo;
use Illuminate\Support\Facades\URL;
use App\Notifications\NewAccountCreated;
use App\utils\Helpers;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Vimeo\Laravel\Facades\Vimeo;
use App\Models\CoordinatorAlliancepartner;
use App\Services\Vi;

class SamplecollectorController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->id ?? auth()->user()->id;
        $users = User::join('roles', 'roles.id', '=', 'users.role_id')
            ->leftJoin('user_details as d1', 'users.id', '=', 'd1.user_id')
            ->select(
                'users.*',
                'roles.name as role',
                'd1.gender'
            )
            ->where('users.role_id', '18');

        $u1 = clone $users;
        $samplecollectors = $u1->limit($request->per_page)->offset(($request->page - 1) * $request->per_page)->get();
        return response()->json([
            'samplecollector' => $samplecollectors,
            'page' => $request->page,
            'total' => $users->count(),
            'per_page' => $request->per_page
        ]);
    }

    public function show(Request $request, int $id)
    {
        $role = User::find($id)->role_id;
        $slot = $request->slot ? false : true;
        $date = $request->date ?  date('Y-m-d', strtotime($request->date)) : date('Y-m-d');

        $user = User::select('users.*')->join('roles', 'roles.id', '=', 'users.role_id')
            ->addSelect('roles.name as user_role')
            ->when($role >= 2, function ($q) {
                $q->leftJoin('user_details', 'user_details.user_id', '=', 'users.id')
                    ->addSelect(
                        'user_details.*',
                        'user_details.id as user_detail_id',
                        'users.role_id as role',
                        'users.id'
                    );
            })->find($id);

        return response()->json([
            'success' => true,
            'samplecollector' => $user
        ]);
    }

    public function list()
    {

        $users = User::join('roles', 'roles.id', '=', 'users.role_id')
            ->leftJoin('user_details as d1', 'users.id', '=', 'd1.user_id')
            ->where('users.role_id', '18')->select('users.id', 'users.name as label')->get();
        return response()->json(['success' => true, 'users' => $users]);
    }



    private function rules($id = "")
    {
        return [
            'email' => [
                'required',
                'unique:users,email,' . $id
            ],
            'phone' => [
                'required',
                'unique:users,phone,' . $id
            ],
            'first_name' => 'required',
            'last_name' => 'required',
            'address' => 'nullable',
            'city' => 'nullable',
            'pincode' => 'nullable',
            'idproof' => 'nullable',
            'dob' => 'nullable',
            'gender' => 'nullable',
            'state' => 'nullable',
            'bank_name' => 'nullable',
            'account_no' => 'nullable',
            'ifsc_code' => 'nullable',
            'cancelled_cheque' => 'nullable',
        ];
    }


    public function store(Request $request, User $user)
    {

        $request->validate($this->rules(), []);
        DB::beginTransaction();
        try {

            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->name = $request->first_name . ' ' . $request->last_name;
            $user->email = $request->email;
            $user->role_id = 18;
            $user->phone = $request->phone;
            $user->created_by = auth()->user()->id;
            $user->save();

            $data = $request->all();
            $data['user_id'] = $user->id;
            $data =  Helpers::storeFiles($request, $data);
            $data['dob'] = (!empty($request->dob)) ? date('Y-m-d', strtotime($request->dob)) : null;
            UserDetail::create($data);
            $role_user = new RoleUser;
            $role_user->user_id = $user->id;
            $role_user->role_id = 18;
            $role_user->save();
            $resetUrl = URL::signedRoute('generate-password', ['user' => $user->id]);
            $user->notify(new NewAccountCreated($resetUrl));
            DB::commit();
            return response()->json(['success' => true, 'message' => "Sample Collector {$user->name} created successfully"]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
            // return response()->json(['success' => false, 'message' => "Something went wrong please try after some time"]);
        }
    }

    public function update(Request $request, int $id)
    {
        $user = User::find($id);
        if ($user) {
            $request->validate($this->rules($id), []);
            DB::beginTransaction();
            try {
                $user->first_name = $request->first_name;
                $user->last_name = $request->last_name;
                $user->name = $request->first_name . ' ' . $request->last_name;
                $user->email =  $request->email;
                $user->role_id = 18;
                $user->phone = $request->phone;
                $user->update();

                $data = $request->all();

                $userDetail = UserDetail::where('user_id', $user->id)->first();
                $data['dob'] = (!empty($request->dob)) ? date('Y-m-d', strtotime($request->dob)) : NULL;
                $data['cancelled_cheque'] = $userDetail->cancelled_cheque;
                $data['idproof'] = $userDetail->idproof;
                $data = Helpers::storeFiles($request, $data);

                // $data['dob'] = $data['dob'] === 'null'  ? null : $data['dob'];

                $userDetail->update($data);

                $role_user = RoleUser::where('user_id', $user->id)->first();
                $role_user->role_id = $user->role_id;
                $role_user->update();

                DB::commit();
                return response()->json(['success' => true, 'message' => "Sample Collector {$user->name} updated successfully"]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
                // return response()->json(['success' => false, 'message' => "Something went wrong please try after some time"]);
            }
        }
    }

    public function generatePassword(Request $request)
    {
        $request->validate([
            'password' => [
                'required',
                'confirmed',
                'regex:/(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%&*()]).{8,}/'
            ]
        ], [
            'password.regex' => 'Your password must contain at least one uppercase, one lowercase, one special character and one digit'
        ]);

        $user = User::find($request->user);

        if ($user) {
            if (!$user->password) {
                $user->password = Hash::make($request->password);
                $user->update();

                return response()->json(['user' => $user, 'type' => 'success',   'message' => 'password generated successfully']);
            } else {
                return response()->json(['user' => null, 'type' => 'danger', 'message' => 'link expired']);
            }
        }
    }


    public function alliancepartners($id)
    {
        $coordinator = User::find($id);
        $userIds = CoordinatorAlliancepartner::where('mh_id', $id)->pluck('alliancepartner_id');
        $alliancepartners = User::whereIn('users.id', $userIds)
            ->where('role_id', 2)
            ->join('coordinator_alliancepartners', 'users.id', '=', 'coordinator_alliancepartners.alliancepartner_id')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->select('users.name', 'users.email', 'users.phone', 'users.id as user_id', 'coordinator_alliancepartners.id', 'user_details.company_name as company_name')->get();
        return response()->json(['success' => true, 'alliancepartners' => $alliancepartners, 'name' => $coordinator->name]);
    }

    public function storeCoodAllianceAssignment(Request $request, $id)
    {
        $request->validate([
            'alliance_partner' => [
                'required'
            ]
        ]);
        try {
            if ($request->has('alliance_partner')) {
                $check = CoordinatorAlliancepartner::where('alliancepartner_id', $request->alliance_partner)->first();
                if (empty($check)) {
                    $CoordinatorAlliancepartner = CoordinatorAlliancepartner::firstOrNew(['mh_id' => $id, 'alliancepartner_id' => $request->alliance_partner]);
                    $CoordinatorAlliancepartner->alliancepartner_id = $request->alliance_partner;
                    $CoordinatorAlliancepartner->mh_id = $id;
                    $CoordinatorAlliancepartner->save();
                    return response()->json(['success' => true, 'type' => 'success',   'message' => 'Alliance Partner assigned successfully']);
                } else {
                    return response()->json(['success' => false, 'message' => "Sample Collector already assigned to this alliance partner"]);
                }
            } else {
                return response()->json(['success' => false, 'message' => "Something went wrong please try after some time"]);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
            // return response()->json(['success' => false, 'message' => "Something went wrong please try after some time"]);
        }
    }

    public function deleteMapping(int $id)
    {
        $CoordinatorAlliancepartner = CoordinatorAlliancepartner::find($id);

        if ($CoordinatorAlliancepartner) {
            $CoordinatorAlliancepartner->delete();
            return response()->json([
                'success' => true,
                'message' => 'Mapping deleted successfully'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'data not found in master'
        ]);
    }

    public function getallianceCood($id)
    {
        $CoordinatorAlliancepartner = CoordinatorAlliancepartner::where('alliancepartner_id', $id)->join('users', 'users.id', '=', 'coordinator_alliancepartners.mh_id')->first();
        if (!empty($CoordinatorAlliancepartner)) {
            return response()->json([
                'success' => true,
                'coordinator' => $CoordinatorAlliancepartner
            ]);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'No coordinator assigned'
            ]);
        }
    }

    public function call(Request $request)
    {
        $no1 = auth()->user()->phone;
        $no2 = $request->phone;
        (new Vi)->call($no1, $no2);
        return response()->json(['success' => true, 'message' => 'call connected successfully ']);
        throw new Exception('Something went wrong while initiating call');
    }

    public function destroy(int $id)
    {
        $user = User::find($id);
        if ($user) {
            // DB::table('chat_messages')->where('from_id', $user->id)->delete();
            // DB::table('chat_messages')->where('to_id', $user->id)->delete();
            DB::table('dagnostichead_alliancepartners')->where('mh_id', $user->id)->delete();
            // DB::table('discount_users')->where('user_id', $user->id)->delete();
            // DB::table('discount_users')->where('user_id', $user->id)->delete();

            // DB::table('enquiries')->where('user_id', $user->id)->delete();
            // DB::table('feedbacks')->where('user_id', $user->id)->delete();

            // DB::table('leads')->where('user_id', $user->id)->delete();
            // DB::table('notifications')->where('user_id', $user->id)->delete();

            DB::table('role_user')->where('user_id', $user->id)->delete();
            DB::table('user_details')->where('user_id', $user->id)->delete();
            //DB::table('workflow_schedules')->where('user_id', $user->id)->delete();
            $user->delete();
            return response()->json(['success' => true, 'message' => 'user deleted successfully']);
        }
        return response()->json(['success' => false, 'message' => 'user not found in storage']);
    }
}
