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
use App\Models\InvestigationTests;
use App\Models\Healthpackages;
use App\Models\Centres;
use App\Models\OnsiteTests;
use App\Models\DiagnosticheadClient;
use App\Models\DiagnosticheadAlliance;
use App\Services\Vi;

class DiagnosticheadController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->id ?? auth()->user()->id;
        $users = User::join('roles', 'roles.id', '=', 'users.role_id')
            ->leftJoin('user_details as d1', 'users.id', '=', 'd1.user_id')
            ->leftJoin('dh_investigation_tests as inv_test', 'inv_test.user_id', '=', 'users.id')
            ->leftJoin('dh_healthpackages as hp', 'hp.user_id', '=', 'users.id')
            ->leftJoin('dh_onsitetests as os_test', 'os_test.user_id', '=', 'users.id')
            ->leftJoin('dh_centres as centres', 'centres.user_id', '=', 'users.id')
            ->leftJoin('diagnostichead_alliance as da', 'da.dh_id', '=', 'd1.user_id')
            ->leftJoin('user_details as allian_detail', 'allian_detail.user_id', '=', 'da.ap_id')
            ->select(
                'users.*',
                'roles.name as role',
                'd1.gender',
                'd1.available_cities',
                'allian_detail.company_name as assigned_alliance',
                DB::raw("CASE WHEN inv_test.id IS NOT NULL THEN 'completed' ELSE 'pending' END AS `investigation_status`"),
                DB::raw("CASE WHEN hp.id IS NOT NULL THEN 'completed' ELSE 'pending' END AS `healthpackage_status`"),
                DB::raw("CASE WHEN os_test.id IS NOT NULL THEN 'completed' ELSE 'pending' END AS `onsites_status`"),
                DB::raw("CASE WHEN centres.id IS NOT NULL THEN 'completed' ELSE 'pending' END AS `centres_status`"),
                DB::raw("CASE WHEN users.status = 1 THEN 'active' ELSE 'inactive' END as `dh_status`")
            )
            ->where('users.role_id', '17')
            ->groupBy('users.id');

        $u1 = clone $users;
        $dhs = $u1->limit($request->per_page)->offset(($request->page - 1) * $request->per_page)->get();
        
        $total_ap = User::where('role_id', 2)->count();
        $total_assigned_ap = DiagnosticheadAlliance::count();
        $available_ap = abs($total_ap - $total_assigned_ap) <= 0 ? false : true ;

        return response()->json([
            'dh' => $dhs,
            'page' => $request->page,
            'total' => $users->count(),
            'per_page' => $request->per_page,
            'available_ap' => $available_ap
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
            'dh' => $user
        ]);
    }

    public function list(Request $request)
    {
        $search = ($request->search) ?? null;
        if (!empty($request->client_id)) {
            $dhs = DiagnosticheadClient::where('client_id', $request->client_id)->pluck('dh_id');
            $users = User::join('roles', 'roles.id', '=', 'users.role_id')
                ->leftJoin('user_details as d1', 'users.id', '=', 'd1.user_id')
                ->where('users.role_id', '17')->whereNotIn('users.id', $dhs)
                ->when($search, function ($q) use($search) {
                    $q->whereJsonContains('d1.service_areas', ['pincode' => $search]);
                    $q->orWhereJsonContains('d1.service_areas', ['address' => $search]);
                    // $q->where('d1.service_areas->address', $search);
                    // $q->orWhere('d1.service_areas->pincode', $search);
                })
                ->select('users.id', 'users.name as label')->get();
        } elseif ($request->alliance_id) {
            $dhs = DiagnosticheadAlliance::where('ap_id', $request->alliance_id)->pluck('dh_id');
            $users = User::join('roles', 'roles.id', '=', 'users.role_id')
                ->leftJoin('user_details as d1', 'users.id', '=', 'd1.user_id')
                ->where('users.role_id', '17')->whereNotIn('users.id', $dhs)
                ->when($search, function ($q) use($search) {
                    $q->whereJsonContains('d1.service_areas', ['pincode' => $search]);
                    $q->orWhereJsonContains('d1.service_areas', ['address' => $search]);
                    // $q->where('d1.service_areas->address', $search);
                    // $q->orWhere('d1.service_areas->pincode', $search);
                })
                ->select('users.id', 'users.name as label')->get();            
        } else {
            $users = User::join('roles', 'roles.id', '=', 'users.role_id')
                ->leftJoin('user_details as d1', 'users.id', '=', 'd1.user_id')
                ->when($search, function ($q) use($search) {
                    $q->whereJsonContains('d1.service_areas', $search);
                })
                ->where('users.role_id', '17')->select('users.id', 'users.name as label')->get();
        }
        
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
            $user->role_id = 17;
            $user->phone = $request->phone;
            $user->save();

            $data = $request->all();
            $data['user_id'] = $user->id;
            $data =  Helpers::storeFiles($request, $data);
            $data['dob'] = (!empty($request->dob)) ? date('Y-m-d', strtotime($request->dob)) : null;
            $data['service_areas'] = (!empty($request->service_areas)) ? json_decode($request->service_areas, true) : null;

            UserDetail::create($data);
            $role_user = new RoleUser;
            $role_user->user_id = $user->id;
            $role_user->role_id = 17;
            $role_user->save();
            $resetUrl = URL::signedRoute('generate-password', ['user' => $user->id]);
            $user->notify(new NewAccountCreated($resetUrl));
            DB::commit();
            return response()->json(['success' => true, 'message' => "Diagnostic Head {$user->name} created successfully"]);
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
                $user->role_id = 17;
                $user->phone = $request->phone;
                $user->update();

                $data = $request->all();
                
                $available_cities = $request->input('available_cities');
                $available_cities = !empty($available_cities) ? explode(',', $available_cities) : [];

                $available_cities_with_status =[];

                if (!empty($available_cities)) {
                    foreach ($available_cities as $key => $city) {
                        $availability['city'] = $city;
                        $availability['status'] = 'active';
                        
                        $available_cities_with_status[] = $availability;
                    }
                }

                $userDetail = UserDetail::where('user_id', $user->id)->first();
                $data['dob'] = (!empty($request->dob)) ? date('Y-m-d', strtotime($request->dob)) : NULL;
                $data['cancelled_cheque'] = $userDetail->cancelled_cheque;
                $data['idproof'] = $userDetail->idproof;
                $data['service_areas'] = (!empty($request->service_areas)) ? json_decode($request->service_areas, true) : null;
                $data = Helpers::storeFiles($request, $data);
                $data['percentage'] = $request->percentage ?? '';
                $data['available_cities'] = $available_cities_with_status;
                $userDetail->update($data);

                $role_user = RoleUser::where('user_id', $user->id)->first();
                $role_user->role_id = $user->role_id;
                $role_user->update();

                DB::commit();
                return response()->json(['success' => true, 'message' => "Diagnostic Head {$user->name} updated successfully"]);
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

    public function getalliancepartners(Request $request, $id) {
        $alliancepartners = DiagnosticheadAlliance::select(DB::raw("concat(allian.name, ' (', allian_detail.company_name, ')') as name"))
        ->join('users as allian', 'allian.id', '=', 'diagnostichead_alliance.ap_id')
        ->join('user_details as allian_detail', 'allian_detail.user_id', '=', 'allian.id')
        ->where('diagnostichead_alliance.dh_id', $id)
        ->get();

        return response()->json(['success' => true, 'alliancepartners' => $alliancepartners]);
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
                    return response()->json(['success' => false, 'message' => "Diagnostic Head already assigned to this alliance partner"]);
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

    public function investigation_list (Request $request) {
        $dh_user_id = $request->input('dh_user_id') ?? auth()->user()->id;
        $investigation_test = InvestigationTests::select('dh_investigation_tests.id', 'dh_investigation_tests.cost', 'test_categories.name as category', 'test_categories.type', 'tests.name as sub_category')
        ->join('test_categories', 'test_categories.id', '=', 'dh_investigation_tests.category_id')
        ->join('tests', 'tests.id', '=', 'dh_investigation_tests.sub_category_id')
        ->where('status', 1)
        ->where('dh_investigation_tests.user_id', $dh_user_id)
        ->orderBy('dh_investigation_tests.id', 'DESC');

        $u1 = clone $investigation_test;
        $investigation_tests = $u1->limit($request->per_page)->offset(($request->page - 1) * $request->per_page)->get();

        return response()->json([
            'investigation_tests' => $investigation_tests,
            'page' => $request->page,
            'total' => $investigation_test->count(),
            'per_page' => $request->per_page
        ]);        
    }

    public function onsitetests_list(Request $request) {
        $dh_user_id = $request->input('dh_user_id') ?? auth()->user()->id;
        $onsitetests = OnsiteTests::select('dh_onsitetests.*', 'test_categories.name as test_category')
        ->leftJoin('test_categories', 'test_categories.id', '=', 'dh_onsitetests.test_category')
        ->where('status', 1)
        ->where('dh_onsitetests.user_id', $dh_user_id);

        $u1 = clone $onsitetests;
        $onsitetests_list = $u1->limit($request->per_page)->offset(($request->page - 1) * $request->per_page)->get();

        return response()->json([
            'onsitetests_list' => $onsitetests_list,
            'page' => $request->page,
            'total' => $onsitetests->count(),
            'per_page' => $request->per_page
        ]);                
    }

    public function healthpackages_list(Request $request) {
        $dh_user_id = $request->input('dh_user_id') ?? auth()->user()->id;

        $healthpackages = Healthpackages::where('status', 1)
        ->where('dh_healthpackages.user_id', $dh_user_id)
        ;

        $u1 = clone $healthpackages;
        $healthpackages_list = $u1->limit($request->per_page)->offset(($request->page - 1) * $request->per_page)->get();
        $healthpackages_list = $healthpackages_list ? $healthpackages_list->toArray() : [];

        if ($healthpackages_list) {
            foreach ($healthpackages_list as $key1 => $_healthpackage) {
                $tests_list = '';

                if(!empty($_healthpackage['tests'])) {
                    $tests_list .= '<ul class="list">';
                    foreach ($_healthpackage['tests'] as $key => $test_arr) {
                        $tests_list .= '<li>' . $test_arr['name'] .'</li>';                        
                    }
                    $tests_list .= '</ul>';
                }

                $healthpackages_list[$key1]['formatted_tests'] = $tests_list;
            }
        }

        return response()->json([
            'healthpackages' => $healthpackages_list,
            'page' => $request->page,
            'total' => $healthpackages->count(),
            'per_page' => $request->per_page
        ]);        
    }

    public function centres_list(Request $request) {
        $dh_user_id = $request->input('dh_user_id') ?? auth()->user()->id;
        $centres = Centres::select('dh_centres.*', 'test_categories.name as available_test_category')
        ->leftJoin('test_categories', 'test_categories.id', '=', 'dh_centres.available_test_category')
        ->where('status', 1)
        ->where('dh_centres.user_id', $dh_user_id);

        if ($request->search_word) {
            $centres->where('dh_centres.full_address', 'LIKE', '%'.$request->search_word.'%');
        }

        $centres->orderBy('dh_centres.id', 'DESC');

        $u1 = clone $centres;
        $centres_list = $u1->limit($request->per_page)->offset(($request->page - 1) * $request->per_page)->get();
        $centres_list = $centres_list ? $centres_list->toArray() : [];

        return response()->json([
            'centres_list' => $centres_list,
            'page' => $request->page,
            'total' => $centres->count(),
            'per_page' => $request->per_page
        ]);        
    }

    public function investigation_store (Request $request) {
        $auth_user_id = auth()->user()->id;
        $records = (array) $request->all();

        if (!empty($records)) {
            foreach ($records as $key => $record) {
                if (isset($record['id']) && !empty($record['id'])) {
                    
                    $data = [];

                    if (isset($record['category']) && !empty((int) $record['category'])) {
                        $data['category_id'] = (int) $record['category'];
                    }

                    if (isset($record['sub_category']) && !empty((int) $record['sub_category'])) {
                        $data['sub_category_id'] = (int) $record['sub_category'];
                    }

                    if (isset($record['type']) && !empty($record['type'])) {
                        $data['type'] = (int) $record['type'];
                    }

                    if (isset($record['cost']) && !empty($record['cost'])) {
                        $data['cost'] = (int) $record['cost'];
                    }

                    if (!empty($data)) {
                        InvestigationTests::where('id', (int) $record['id'])->update($data);
                    }

                }else{
                    $is_exists = InvestigationTests::where(
                        [
                            'category_id' => (int) $record['category'], 
                            'sub_category_id' => (int) $record['sub_category']
                        ]
                    )->exists();

                    if ($is_exists) {
                        return response()->json(['success' => false, 'message' => 'Record alread exists!']);
                    } else {                        
                        InvestigationTests::insert([
                            'user_id' => $auth_user_id,
                            'category_id' => (int) $record['category'], 
                            'sub_category_id' => (int) $record['sub_category'], 
                            'type' => $record['type'], 
                            'cost' => $record['cost']
                        ]);
                    }
                }
            }

            return response()->json(['success' => true, 'message' => 'Records Saved Successfully']);
        }

        return response()->json(['success' => false, 'message' => 'Invalid data']);
    }

    public function healthpackages_store (Request $request) {

        $request->validate([
            'name' => 'required',
            'num_of_test' => 'required',
        ]);

        $auth_user_id = auth()->user()->id;

        if ($request->id) {
            $healthpackage = Healthpackages::find((int) $request->id);
        }else{
            $healthpackage = new Healthpackages();            
        }

        $package_tests = [];
        if ($request->tests) {
            $package_tests = json_decode($request->tests, true);
            foreach ($package_tests as $key => $test) {
                $test_file = 'vectorimg_' . $key;
                if ($request->hasFile($test_file)) {
                    $package_tests[$key]['vector_image'] = $request->{$test_file}->store('dh/healthpackages');                    
                }    
            }
        }

        $healthpackage->user_id = $auth_user_id;
        $healthpackage->name = $request->name;
        $healthpackage->num_of_test = $request->num_of_test;
        $healthpackage->tests = $package_tests;
        $healthpackage->cost = $request->cost;
        $healthpackage->notes = $request->notes;

        if( $request->hasFile('logo') )
        {
            $ext = $request->logo->getClientOriginalExtension();
            if( in_array(strtolower($ext), ['png','svg','jpg','jpeg','gif'])) {
                $healthpackage->logo = $request->logo->store('dh/healthpackages');
            }
        }

        $healthpackage->save();

        return response()->json(['success' => true, 'message' => 'Records Saved Successfully']);

    }

    public function centres_store(Request $request) {
        $auth_user_id = auth()->user()->id;
        $records = (array) $request->all();

        if (!empty($records)) {
            foreach ($records as $key => $record) {
                if (isset($record['id']) && !empty($record['id'])) {
                    Centres::where('id', (int) $record['id'])->update([
                        'branch' => $record['branch'], 
                        'full_address' => $record['full_address'], 
                        'contact_number' => $record['contact_number'], 
                        'map_code' => $record['map_code'],
                        'available_test_category' => (int) $record['available_test_category'],
                        'delivery_executive_number' => $record['delivery_executive_number'],
                    ]);
                }else{                    
                    Centres::insert([
                        'user_id' => $auth_user_id,
                        'branch' => $record['branch'], 
                        'full_address' => $record['full_address'], 
                        'contact_number' => $record['contact_number'], 
                        'map_code' => $record['map_code'],
                        'available_test_category' => (int) $record['available_test_category'],
                        'delivery_executive_number' => $record['delivery_executive_number'],
                    ]);
                }
            }

            return response()->json(['success' => true, 'message' => 'Records Saved Successfully']);
        }

        return response()->json(['success' => false, 'message' => 'Invalid data']);
    }

    public function onsitetests_store (Request $request) {
        $request->validate([
            'test_category' => 'required',
            'name' => 'required',
            'part' => 'required',
            'view' => 'required',
            'cost' => 'required'
        ]);

        $auth_user_id = auth()->user()->id;

        if ($request->id) {
            $onsiteTest = OnsiteTests::find((int) $request->id);
        }else{
            $onsiteTest = new OnsiteTests();            
            $onsiteTest->user_id = $auth_user_id;
        }

        if ($request->input('test_category')) {
            $onsiteTest->test_category = $request->input('test_category');
        }

        $onsiteTest->name = $request->input('name');
        $onsiteTest->part = $request->input('part');
        $onsiteTest->view = $request->input('view');
        $onsiteTest->cost = $request->input('cost');

        if ($request->input('notes')) {
            $onsiteTest->notes = $request->input('notes');
        }

        if( $request->hasFile('logo') )
        {
            $ext = $request->logo->getClientOriginalExtension();
            if( in_array(strtolower($ext), ['png','svg','jpg','jpeg','gif'])) {
                $onsiteTest->logo = $request->logo->store('dh/onsite-tests');
            }
        }

        $onsiteTest->save();

        return response()->json(['success' => true, 'message' => 'Records Saved Successfully']);
    }

    public function settings_store (Request $request) {
        
    }

}
