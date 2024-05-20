<?php

namespace App\Http\Controllers;

use App\Models\AlianClient;
use App\Models\AlianConsultant;
use App\Models\AlliancePartner;
use App\Models\User;
use App\Models\RoleUser;
use App\Models\UserDetail;
use App\Models\Package;
use App\Models\PackageUser;
use Illuminate\Support\Facades\URL;
use App\Notifications\NewAccountCreated;
use App\utils\Helpers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\Vi;

class AlliancePartnerController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->filters ?? false;
        $role = auth()->user()->role_id;
        if ($filters && gettype($filters) === 'string') {
            $filters = json_decode($filters, true);
        }
        $users =  User::join('roles', 'roles.id', '=', 'users.role_id')
            ->join('user_details', 'user_details.user_id', '=', 'users.id')
            ->leftJoin('diagnostichead_alliance as dha', 'dha.ap_id', '=', 'users.id')
            ->leftJoin('users as dh', 'dh.id', '=', 'dha.dh_id')
            ->where('users.role_id', 2)
            ->select(
                'users.*',
                'roles.name as role',
                'user_details.url',
                'user_details.logo',
                'user_details.signature_img',
                'user_details.banner_img',
                'user_details.company_name',
                'user_details.company_contact',
                'user_details.welcome_message',
                'user_details.website_name',
                'user_details.address',
                'user_details.pincode',
                'user_details.group',
                'user_details.type',
                'dh.name as diagnostichead'
            )->when($filters && !empty($filters), function ($q) use ($filters) {
                if (isset($filters['group'])) {
                    $q->where('user_details.group', $filters['group']);
                }
                if (isset($filters['type'])) {
                    $q->where('user_details.type', $filters['type']);
                }
                $q->where('users.id', '!=', auth()->user()->id);
            })->when($role, function ($q) use ($role) {
                if ($role === 10) {
                    $alliancepartners = DB::table('coordinator_alliancepartners')->where('coordinator_id', auth()->user()->id)->pluck('alliancepartner_id')->toArray();
                    $q->whereIn('users.id', $alliancepartners);
                }
                if ($role === 15) {
                    $marketing_executives = User::where('created_by', auth()->user()->id)->where('role_id', 16)->pluck('id');
                    $alliancepartners = DB::table('marketinghead_alliancepartners')->where('mh_id', auth()->user()->id)->pluck('alliancepartner_id')->toArray();
                    $me_alliancepartners = DB::table('marketingexecutive_alliancepartners')->whereIn('me_id', $marketing_executives)->pluck('alliancepartner_id')->toArray();
                    $q->whereIn('users.id', array_merge($alliancepartners, $me_alliancepartners));
                }
                if ($role === 16) {
                    $alliancepartners = DB::table('marketingexecutive_alliancepartners')->where('me_id', auth()->user()->id)->pluck('alliancepartner_id')->toArray();
                    $q->whereIn('users.id', $alliancepartners);
                }
            });
        $u1 = clone $users;
        $partners = $u1->limit($request->per_page)->offset(($request->page - 1) * $request->per_page)->get();
        foreach ($partners as $key => $partner) {
            $coordinators = \App\Models\CoordinatorAlliancepartner::where('alliancepartner_id', $partner->id)->pluck('coordinator_id');
            $coordinators = User::find($coordinators)->pluck('name');
            $partners[$key]->coordinators = $coordinators;
            if ($partner->created_by) {
                $added_by = User::where('id', $partner->created_by)->first();
                $partners[$key]['added_by'] = $added_by->name;
            }
            $clients = AlianClient::where('aliance_id', $partner->id)->count();
            $partners[$key]['clients_count'] = $clients;
            $partners[$key]['revenue'] = 0;
        }
        return response()->json([
            'partners' =>  $partners,
            'page' => $request->page,
            'total' => $users->count(),
            'per_page' => $request->per_page
        ]);
    }

    public function show($id)
    {
        return response()->json([
            'success' => true,
            'partners' => User::join('roles', 'roles.id', '=', 'users.role_id')
                ->join('user_details', 'user_details.user_id', '=', 'users.id')
                ->where('users.role_id', 2)
                ->select(
                    'users.id',
                    'users.name',
                    'users.first_name',
                    'users.last_name',
                    'users.role_id as role',
                    'users.email',
                    'users.phone',
                    'user_details.url',
                    'user_details.logo',
                    'user_details.signature_img',
                    'user_details.banner_img',
                    'user_details.company_name',
                    'user_details.company_contact',
                    'user_details.welcome_message',
                    'user_details.website_name',
                    'user_details.address',
                    'user_details.pincode',
                    'user_details.group',
                    'user_details.type',
                )
                ->where('users.id', $id)->first()
        ]);
    }
    private function rules($id = "")
    {
        return [
            'email' => [
                'required',
                'unique:users,email,' . $id
            ],
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => [
                'required',
                'unique:users,phone,' . $id
            ],
            'role' => 'required',
            'group' => 'required',
            // 'type' => 'required',
            'company_name' => 'required',
            'url' => 'required',
            // 'company_contact' => 'required',
            // 'welcome_message' => 'required',
        ];
    }


    public function store(Request $request, User $user)
    {
        $request->validate($this->rules());
        DB::beginTransaction();
        try {

            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->name = $request->first_name . ' ' . $request->last_name;
            $user->email = $request->email;
            $user->role_id = 2;
            $user->phone = $request->phone;
            $user->created_by = auth()->user()->id;
            $user->save();

            $data = $request->all();
            $data['certificates'] = [];
            $data['user_id'] = $user->id;
            $data =  Helpers::storeFiles($request, $data);

            UserDetail::create($data);

            $role_user = new RoleUser;
            $role_user->user_id = $user->id;
            $role_user->role_id = 2;
            $role_user->save();

            $resetUrl = URL::signedRoute('generate-password', ['user' => $user->id]);

            $user->notify(new NewAccountCreated($resetUrl));

            DB::commit();

            if (auth()->user()->role_id == 15) {
                DB::table('marketinghead_alliancepartners')->insert(['mh_id' => auth()->user()->id, 'alliancepartner_id' => $user->id]);
                $notification_to = User::where('role_id', '16')->where('created_by', auth()->user()->id)->pluck('id');
            }

            if (auth()->user()->role_id == 16) {
                DB::table('marketingexecutive_alliancepartners')->insert(['me_id' => auth()->user()->id, 'alliancepartner_id' => $user->id]);
                $user->approve_status = '2';
                $user->save();
                $notification_to = array(auth()->user()->created_by);
            }

            foreach ($notification_to as $notification_to) {
                \App\Models\Notification::create([
                    'user_id' => $notification_to,
                    'message' => "New AP Joined - " . $request->first_name . ' ' . $request->last_name,
                    'meta' => [
                        'ap_id' => $user->id
                    ]
                ]);
            }


            return response()->json(['success' => true, 'message' => "alliance partner {$user->name} created successfully"]);
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return response()->json(['success' => false, 'message' => "Something went wrong please try after some time"]);
        }
    }


    public function update(Request $request, int $id)
    {
        $user = User::find($id);
        if ($user) {
            $request->validate($this->rules($id));
            DB::beginTransaction();
            try {
                $user->first_name = $request->first_name;
                $user->last_name = $request->last_name;
                $user->name = $request->first_name . ' ' . $request->last_name;
                $user->email =  $request->email;
                $user->phone = $request->phone;
                $user->update();

                $data = $request->all();
                $userDetail = UserDetail::where('user_id', $user->id)->first();
                $data['dob'] = $request->dob;
                $data['cancelled_cheque'] = $userDetail->cancelled_cheque;
                $data['certificates'] = $userDetail->certificates;
                $data['signature_img'] = $userDetail->signature_img;
                $data['banner_img'] = $userDetail->banner_img;
                $data['logo'] = $userDetail->logo;
                $data = Helpers::storeFiles($request, $data);

                $userDetail->update($data);

                DB::commit();
                return response()->json(['success' => true, 'message' => "alliance partner {$user->name} updated successfully"]);
            } catch (\Exception $e) {
                DB::rollback();
                dd($e);
                return response()->json(['success' => false, 'message' => "Something went wrong please try after some time"]);
            }
        }
    }
    public function list(Request $request)
    {
        $role = \App\Models\Role::find(auth()->user()->role_id);
        $for_dh = $request->input('dh_id');

        $alliancepartners = User::where('status', 1)
            ->where('users.role_id', 2)
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->leftJoin('diagnostichead_alliance', 'diagnostichead_alliance.ap_id', '=', 'users.id')
            ->when($role, function ($q) use ($role) {
                $alliancepartners = DB::table('coordinator_alliancepartners')->where('coordinator_id', auth()->user()->id)->pluck('alliancepartner_id')->toArray();
                if ($role->name === 'coordinator') {
                    $q->whereIn('users.id', $alliancepartners);
                }
            })
            ->when($for_dh, function($q) {
                $q->whereNull('diagnostichead_alliance.id');
            })
            ->select('users.id', 'user_details.company_name as label')
            ->groupBy('users.id')
            ->get();
        return response()->json(['success' => true, 'partners' => $alliancepartners]);
    }

    public function partnerTypes()
    {
        $type = request('type') ?? null;
        $partnerTypes = User::where('role_id', 2)->distinct('user_details.type')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->when($type, function ($q) use ($type) {
                $q->where('user_details.type', $type);
            })->pluck('user_details.type');
        return response()->json([
            'success' => true,
            'partnerTypes' => $partnerTypes
        ]);
    }


    public function bannerlogo(Request $request)
    {
        $request->validate([
            'url' => 'required'
        ]);

        $data = UserDetail::where('url', $request->url)->select('banner_img', 'logo', 'welcome_message', 'user_id')->first();



        if ($data) {
            return response()->json([
                'success' => true,
                'user' => $data
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'data not found in master'
        ]);
    }


    public function programs(Request $request)
    {
        $request->validate([
            'url' => 'required'
        ]);

        $data = UserDetail::where('url', $request->url)->select('banner_img', 'logo', 'welcome_message', 'user_id')->first();
        if ($data) {
            $ids = PackageUser::where('user_id', $data->user_id)->pluck('package_id');
            $plan = $request->plan ?? null;
            $type = $request->type ?? null;
            $sort = ($request->sort == 'true') ?? null;
            $recent = ($request->recent == 'true') ?? null;

            $packages =  Package::when($recent, function ($q) {
                $q->orderBy('id', 'desc');
            })
                ->when($sort == true, function ($q) {
                    $q->orderBy('title');
                })
                ->when($plan, function ($q) use ($plan) {
                    $q->whereRaw('json_contains(plans, \'["' . $plan . '"]\')');
                })
                ->whereIn('id', $ids)
                ->when($type, function ($q) use ($type) {
                    $q->where('type', $type);
                });
            $p1 = clone $packages;
            return response()->json([
                'success' => true,
                'user' => $data,
                'total' => $p1->count(),
                'page' => $request->page,
                'per_page' => $request->per_page,
                'packages' =>  $p1
                    ->limit($request->per_page)
                    ->offset(($request->page - 1) * $request->per_page)
                    ->get(),
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'data not found in master'
        ]);
    }


    public function saveMapping(Request $request)
    {
        AlianConsultant::insert([
            'consultant_id' => $request->consultant_id,
            'aliance_id' =>  $request->aliance_id ?? auth()->user()->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Mapping updated successfully'
        ]);
    }

    public function deleteMapping(int $id)
    {
        $allianConsultant = AlianConsultant::find($id);

        if ($allianConsultant) {
            $allianConsultant->delete();
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

    public function callcoordinator(Request $request)
    {
        $no1 = auth()->user()->phone;
        $user = User::find($request->id);
        if ($user) {
            $no2 = $user->phone;
            (new Vi)->call($no1, $no2);
            return response()->json(['success' => true, 'message' => 'call connected successfully ']);
        }
        throw new Exception('Something went wrong while initiating call');
    }

    public function dashboard()
    {
        $leads = \App\Models\Lead::where('alliancepartner_id', auth()->user()->id)->count();
        $clients = \App\Models\AlianClient::where('aliance_id', auth()->user()->id)->count();
        $client_ids = \App\Models\AlianClient::where('aliance_id', auth()->user()->id)->pluck('client_id');
        $revenue = \App\Models\PackageUser::whereIn('user_id', $client_ids)->where('enrolled', '1')->sum('cost');
        return response()->json([
            'success' => true,
            'leads' => $leads,
            'clients' => $clients,
            'revenue' => $revenue,
            'message' => 'Mapping deleted successfully'
        ]);
    }
    public function destroy(int $id)
    {
        $user = User::find($id);
        if ($user) {
            DB::table('alian_client')->where('aliance_id', $user->id)->delete();
            DB::table('alian_consultants')->where('aliance_id', $user->id)->delete();
            DB::table('alliance_partners')->where('user_id', $user->id)->delete();
            DB::table('chat_messages')->where('from_id', $user->id)->delete();
            DB::table('chat_messages')->where('to_id', $user->id)->delete();
            DB::table('client_referrals')->where('referred_by', $user->id)->delete();
            DB::table('client_referrals')->where('referred_to', $user->id)->delete();
            DB::table('coordinator_alliancepartners')->where('alliancepartner_id', $user->id)->delete();
            DB::table('discount_coupons')->where('user_id', $user->id)->delete();
            DB::table('discount_users')->where('user_id', $user->id)->delete();
            DB::table('enquiries')->where('user_id', $user->id)->delete();
            DB::table('feedbacks')->where('user_id', $user->id)->delete();
            DB::table('group_users')->where('user_id', $user->id)->delete();
            DB::table('html_questionnaire_users')->where('user_id', $user->id)->delete();
            DB::table('leads')->where('alliancepartner_id', $user->id)->delete();
            DB::table('reminders')->where('user_id', $user->id)->delete();
            DB::table('package_users')->where('user_id', $user->id)->delete();
            DB::table('role_user')->where('user_id', $user->id)->delete();
            DB::table('user_details')->where('user_id', $user->id)->delete();
            //DB::table('users')->where('user_id', $user->id)->delete();
            $user->delete();
            return response()->json(['success' => true, 'message' => 'user deleted successfully']);
        }
        return response()->json(['success' => false, 'message' => 'user not found in storage']);
    }

    public function approve($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->approve_status = '1';
            $user->save();
            return response()->json(['success' => true, 'message' => 'user got approved successfully']);
        }
        return response()->json(['success' => false, 'message' => 'user not found in storage']);
    }
}
