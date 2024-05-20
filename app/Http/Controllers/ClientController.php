<?php

namespace App\Http\Controllers;

use App\Models\AlianClient;
use App\Models\AlliancePartner;
use App\Models\ClientConsultant;
use App\Models\ClientReferral;
use App\Models\CoordinatorAlliancepartner;
use App\Models\GroupUser;
use App\Models\HealthcoachClient;
use App\Models\PackageUser;
use App\Models\PlanUser;
use App\Models\QuestionnaireUser;
use App\Models\RecipeUser;
use App\Models\User;
use App\Models\RoleUser;
use App\Models\UserDetail;
use App\Models\UserVideo;
use App\Models\DiagnosticheadClient;
use App\Models\DiagnosticheadAlliance;
use Illuminate\Support\Facades\URL;
use App\Notifications\NewAccountCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

use Illuminate\Notifications\Messages\MailMessage;

use Mail;

class ClientController extends Controller
{

    public function index(Request $request)
    {
        $id = $request->id ?? auth()->user()->id;
        $for_ap = $request->for_ap ?? false;
        $userDetail = UserDetail::where('user_id', $id)->first();
        if ($request->role) {
            $role = $request->role;
        } else {
            if (auth()->user()->role_id === 1 || auth()->user()->role_id === 17) {
                $role = null;
            } else {
                $role = \App\Models\Role::find(auth()->user()->role_id)->name;
            }
            $is_consultant = \App\Models\Role::find(auth()->user()->role_id)->is_consultant;
            if ($is_consultant == '1') {
                $role = 'consultant';
            }
        }

        $users = User::join('roles', 'roles.id', '=', 'users.role_id')
            ->leftJoin('alian_client', 'users.id', '=', 'alian_client.client_id')
            ->leftJoin('users as partner', 'partner.id', '=', 'alian_client.aliance_id')
            ->leftJoin('user_details as d1', 'users.id', '=', 'd1.user_id')
            ->leftJoin('user_details as d2', 'partner.id', '=', 'd2.user_id')
            ->leftJoin('client_group_mappings as mapping', 'mapping.client_id', '=', 'users.id')
            ->leftJoin('client_groups', 'client_groups.id', '=', 'mapping.group_id')
            //->where('alian_client.referred', 1)
            ->select(
                'users.*',
                'roles.name as role',
                'alian_client.aliance_id as alliance_partner',
                'alian_client.status as client_status',
                'alian_client.apptraining_status as apptraining_status',
                'alian_client.hc_id as hc_id',
                'alian_client.incharge_id as ic_id',
                'alian_client.cis_id as cis_id',
                'd2.company_name as referred_partner',
                'd1.gender',
                'd1.marital_status',
                'd1.dob',
                'client_groups.name as group_name',
            )
            ->where('users.role_id', 3)
            ->when($id && $role, function ($q) use ($id, $role, $userDetail) {
                switch ($role) {
                    case 'consultant':
                        $ids = ClientConsultant::where('consultant_id', $id)->pluck('client_id');
                        break;
                    case 'alliance-partner':
                        $ids = AlianClient::where('aliance_id', $id)->pluck('client_id');
                        break;
                    case 'coordinator':
                        $alliancepartners = CoordinatorAlliancepartner::where('coordinator_id', $id)->pluck('alliancepartner_id');
                        $ids = AlianClient::whereIn('aliance_id', $alliancepartners)->pluck('client_id');
                        break;
                    case 'healthcoach':
                        $ids = AlianClient::where('hc_id', auth()->user()->id)->orWhere('cis_id', auth()->user()->id)->pluck('client_id');
                        break;
                    default:
                        $ids = AlianClient::where('aliance_id', $id)->pluck('client_id');
                }
                $q->whereIn('users.id', $ids);
            })->when(auth()->user()->role_id == 17, function ($q) {
                $ids = DiagnosticheadClient::where('dh_id', auth()->user()->id)->pluck('client_id');
                $q->whereIn('users.id', $ids);
            })->when($for_ap, function ($q) use($for_ap) {
                $q->whereIn('alian_client.aliance_id', [$for_ap]);
            });

        $u1 = clone $users;
        return response()->json([
            'clients' => $u1->limit($request->per_page)->offset(($request->page - 1) * $request->per_page)->orderBy('users.id', 'DESC')->get(),
            'page' => $request->page,
            'total' => $users->count(),
            'per_page' => $request->per_page
        ]);
    }

    public function isClientExist(Request $request)
    {
        $user = User::where('phone', $request->phone_number)->first();
        if (!$user) {
            return response()->json([
                'success' => true,
                'user_status' => "not_present",
                'message' => "User not exist"
            ]);
        } elseif ($user->email_verified_at == null) {
            return response()->json([
                'success' => false,
                'user_status' => "not_verified",
                'message' => "User present but not verified email"
            ]);
        }
        return response()->json([
            'success' => false,
            'user_status' => "present",
            'message' => "User Already present"
        ]);
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
            $user->role_id = 3;
            $user->phone = $request->phone;
            $user->save();

            $role_user = new RoleUser;
            $role_user->user_id = $user->id;
            $role_user->role_id = 3;
            $role_user->save();



            DB::table('alian_client')->insert([
                'aliance_id' => $request->alliance_partner,
                'client_id' => $user->id,
                'referred' => true
            ]);

            $packages = PackageUser::where('user_id', $request->alliance_partner)->distinct('package_id')
                ->select('package_id as id', 'data')->get();

            if ($packages->count()) {
                foreach ($packages as $package) {
                    $data[] = [
                        'package_id' => $package->id,
                        'user_id' => $user->id,
                        'cost' => 0,
                        'data' => json_encode($package->data),
                        'joined_date' => date('Y-m-d')
                    ];
                }

                PackageUser::insert($data);
            }

            if ($request->has('consultant_id')) {
                DB::table('client_consultants')->insert([
                    'consultant_id' => $request->consultant_id,
                    'client_id' => $user->id,
                    'referred' => true
                ]);
            }

            $resetUrl = URL::signedRoute('generate-password', ['user' => $user->id]);

            $user->notify(new NewAccountCreated($resetUrl));


            $data = $request->all();
            $data['user_id'] = $user->id;
            $data['dob'] = (!empty($request->dob)) ? date('Y-m-d', strtotime($request->dob)) : NULL;
            $data['wedding_date'] = (!empty($request->wedding_date)) ? date('Y-m-d', strtotime($request->wedding_date)) : NULL;
            UserDetail::create($data);
            DB::commit();

            return response()->json([
                'success' => true,
                'password_url' => $resetUrl,
                'user_id' => $user->id,
                'message' => "client {$user->name} created successfully"
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            \Log::info('error', ['error'  => $e]);
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
                $user->role_id = 3;
                $user->phone = $request->phone;
                $user->update();

                DB::commit();

                $data = $request->all();
                $userDetail = UserDetail::where('user_id', $user->id)->first();
                $data['dob'] = (!empty($request->dob)) ? date('Y-m-d', strtotime($request->dob)) : NULL;
                $userDetail->update($data);

                if ($request->has('consultant_id')) {
                    $ClientConsultant = ClientConsultant::firstOrNew(['client_id' => $user->id, 'referred' => true]);
                    $ClientConsultant->consultant_id = $request->consultant_id;
                    $ClientConsultant->save();
                }
                if ($request->has('alliance_partner')) {
                    $alian_client = AlianClient::updateOrCreate(['aliance_id' => $request->alliance_partner, 'client_id' => $user->id], ['aliance_id' => $request->alliance_partner, 'client_id' => $user->id]);
                }
                return response()->json(['success' => true, 'message' => "client {$user->name} updated successfully"]);
            } catch (\Exception $e) {
                DB::rollback();
                dd($e);
                return response()->json(['success' => false, 'message' => "Something went wrong please try after some time"]);
            }
        }
    }

    public function list()
    {
        $partners = request('partners') ?? null;
        $consultant = request('consultant') ?? null;
        $usersIds = null;

        $video = request('video') ?? null;
        $group = request('group') ?? null;
        $questionnaire = request('questionnaire') ?? null;
        $plan = request('plan') ?? null;
        $recipe = request('recipe') ?? null;


        if ($video) {
            $usersIds = UserVideo::where('video_id', $video)->pluck('user_id')->toArray();
        }
        if ($group) {
            $usersIds = GroupUser::where('group_id', $group)->pluck('user_id')->toArray();
        }

        if ($questionnaire) {
            $usersIds = QuestionnaireUser::where('questionnaire_id', $questionnaire)->pluck('user_id')->toArray();
        }

        if ($plan) {
            $usersIds = PlanUser::where('diet_plan_id', $plan)->pluck('user_id')->toArray();
        }

        if ($recipe) {
            $usersIds = RecipeUser::where('recipe_id', $recipe)->pluck('user_id')->toArray();
        }



        $clients = User::join('alian_client', 'alian_client.client_id', '=', 'users.id')
            ->when($partners, function ($q) use ($partners) {
                $q->whereIn('alian_client.aliance_id', $partners);
            })
            ->when($consultant, function ($q) use ($consultant) {
                $usersId_s = ClientConsultant::whereIn('consultant_id', $consultant)->pluck('client_id');
                $q->whereIn('users.id', $usersId_s);
            })
            ->when($usersIds, function ($q) use ($usersIds) {
                $q->whereNotIn('users.id', $usersIds);
            })
            ->select('users.id', 'users.name as label')->get();

        return response()->json(['success' => true, 'clients' => $clients]);
    }

    public function referfriend(Request $request)
    {
        $alian_client = AlianClient::join('user_details', 'alian_client.aliance_id', '=', 'user_details.user_id')->where('alian_client.client_id', auth()->user()->id)->select('user_details.*')->first();
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:leads,email',
            'user_id' => 'sometimes',
            'mobile' => 'required',
            'concern' => 'required'
        ]);
        $lead = new \App\Models\Lead;
        $lead->name = $request->name;
        $lead->email = $request->email;
        $lead->phone = $request->mobile;
        $lead->concern = $request->concern;
        $lead->dob = date('Y-m-d', strtotime($request->dob));
        $lead->age = $request->age;
        $lead->alliancepartner_id = $alian_client->aliance_id;
        $lead->save();
        $mailmessage = (new MailMessage)
            ->line('Hai ' . $request->name)
            ->line('You are referred by ' . auth()->user()->name . ' to join as a lead')
            ->line('Please feel free to contact us anytime')
            ->line('Thank You')->render();
        Mail::send([], [], function ($message) use (&$mailmessage, $request) {
            $message->to($request->email)
                ->subject('Positive health app  - Refer')
                ->setBody($mailmessage, 'text/html'); // for HTML rich messages
        });
        return response()->json([
            'success' => true,
            'message' => 'Mail sent successfully.',
        ]);
    }

    public function updatestatus(Request $request)
    {
        $alian_client = AlianClient::where('alian_client.client_id', $request->id)->select('alian_client.*')->first();
        $alian_client->status = 'Completed';
        $alian_client->save();
        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
        ]);
    }
    public function updatetrainingstatus(Request $request)
    {
        $alian_client = AlianClient::where('alian_client.client_id', $request->id)->select('alian_client.*')->first();
        $alian_client->apptraining_status = 'Completed';
        $alian_client->save();
        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
        ]);
    }

    public function storehcassignment(Request $request)
    {
        $alian_client = AlianClient::where('alian_client.client_id', $request->client_id)->select('alian_client.*')->first();
        $alian_client->hc_id = $request->hc_id;
        $alian_client->save();
        $client = User::find($request->client_id);
        \App\Models\Notification::create([
            'user_id' => $request->hc_id,
            'message' => "New client assigned - " . $client->name,
            'meta' => [
                'client_id' => $request->client_id
            ]
        ]);
        return response()->json([
            'success' => true,
            'message' => 'HC assigned updated successfully.',
        ]);
    }
    public function storedhassignment(Request $request)
    {
        // $dh = new DiagnosticheadClient;
        // $dh->client_id = $request->client_id;
        // $dh->dh_id = $request->dh_id;
        // $dh->save();

        $dh = DiagnosticheadClient::updateOrCreate(
            [
                'client_id'   => $request->client_id,
                'dh_id' => $request->dh_id
            ],
            [
                'client_id'   => $request->client_id,
                'dh_id' => $request->dh_id
            ],
        );

        $client = User::find($request->client_id);
        \App\Models\Notification::create([
            'user_id' => $request->dh_id,
            'message' => "New client assigned - " . $client->name,
            'meta' => [
                'client_id' => $request->client_id
            ]
        ]);
        return response()->json([
            'success' => true,
            'message' => 'DH assigned updated successfully.',
        ]);
    }
    public function storeinchargeassignment(Request $request)
    {
        $alian_client = AlianClient::where('alian_client.client_id', $request->client_id)->select('alian_client.*')->first();
        $alian_client->incharge_id = $request->ic_id;
        $alian_client->save();
        $client = User::find($request->client_id);
        \App\Models\Notification::create([
            'user_id' => $request->ic_id,
            'message' => "New incharge assigned - " . $client->name,
            'meta' => [
                'client_id' => $request->client_id,
                'role' => 3
            ]
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Incharge assigned updated successfully.',
        ]);
    }

    public function programlist($id)
    {
        $packages = PackageUser::where('package_users.user_id', $id)->join('packages', 'packages.id', '=', 'package_users.package_id')->select('packages.*', 'package_users.*')->get();
        $userprograms = array();
        $paidprograms = array();
        foreach ($packages as $package) {
            $display = true;
            if (auth()->user()->role_id == 2) {
                $checkpackage = PackageUser::where('package_users.user_id', auth()->user()->id)->where('package_id', $package->package_id)->first();
                if (empty($checkpackage)) {
                    $display = false;
                }
            }
            $alian_client = AlianClient::where('alian_client.client_id', $id)->join('users', 'users.id', '=', 'alian_client.hc_id')->select('users.name as healthcoach_name')->first();
            $plan = (!empty($package->data)) ? array_key_first($package->data) : '';
            $key = array_search($plan, json_decode($package->plans, TRUE));
            if ($display) {
                $userprograms[] = array(
                    'id' => $package->id,
                    'program_name' => $package->title,
                    'sessions' => (!empty(json_decode($package->sessions, TRUE))) ? json_decode($package->sessions, TRUE)[$key] : '',
                    'plan' => (!empty($package->data)) ? array_key_first($package->data) : '',
                    'hc' => (!empty($alian_client)) ? $alian_client->healthcoach_name : '',
                    'transaction_date' => ($package->transaction_date) ? date('d-m-Y', strtotime($package->transaction_date)) : '',
                    'joined_date' => ($package->joined_date) ? date('d-m-Y', strtotime($package->joined_date)) : '',
                    'status' => $package->status,
                    'start_date' => (!empty($package->start_date)) ?  date('d-m-Y', strtotime($package->start_date)) : '-',
                    'end_date' => (!empty($package->start_date)) ?  date('d-m-Y', strtotime($package->end_date)) : '-',
                );

                // if (!empty($package->transaction_date)) {
                    $paidprograms[] = array(
                        'id' => $package->id,
                        'program_name' => $package->title,
                        'sessions' => (!empty(json_decode($package->sessions, TRUE))) ? json_decode($package->sessions, TRUE)[$key] : '',
                        'plan' => (!empty($package->data)) ? array_key_first($package->data) : '',
                        'hc' => (!empty($alian_client)) ? $alian_client->healthcoach_name : '',
                        'transaction_date' => ($package->transaction_date) ? date('d-m-Y', strtotime($package->transaction_date)) : '',
                        'joined_date' => ($package->joined_date) ? date('d-m-Y', strtotime($package->joined_date)) : '',
                        'status' => $package->status,
                        'start_date' => (!empty($package->start_date)) ?  date('d-m-Y', strtotime($package->start_date)) : '-',
                        'end_date' => (!empty($package->start_date)) ?  date('d-m-Y', strtotime($package->end_date)) : '-',
                    );
                // }
            }
        }
        return response()->json([
            'userprograms' => $userprograms,
            'paidprograms' => $paidprograms
        ]);
    }
    public function update_programstatus(Request $request)
    {
        $user_package = PackageUser::find($request->id);
        $user_package->status = $request->status;
        $user_package->save();
        return response()->json([
            'success' => true,
            'message' => 'Program status updated successfully!',
        ]);
    }

    public function update_userprogram(Request $request)
    {
        $user_package = PackageUser::find($request->id);
        $user_package->start_date = date('Y-m-d', strtotime($request->start_date));
        $user_package->end_date = date('Y-m-d', strtotime($request->end_date));
        $user_package->save();
        return response()->json([
            'success' => true,
            'message' => 'Program data updated successfully!',
        ]);
    }

    public function destroy(int $id)
    {
        $user = User::find($id);
        if ($user) {
            DB::table('alian_client')->where('client_id', $user->id)->delete();
            DB::table('bookings')->where('booked_by', $user->id)->delete();
            DB::table('case_sheets')->where('client_id', $user->id)->delete();
            DB::table('client_consultants')->where('client_id', $user->id)->delete();
            DB::table('client_group_mappings')->where('client_id', $user->id)->delete();
            DB::table('client_referrals')->where('client_id', $user->id)->delete();
            DB::table('discount_users')->where('user_id', $user->id)->delete();
            DB::table('enquiries')->where('user_id', $user->id)->delete();
            DB::table('feedbacks')->where('client_id', $user->id)->delete();
            DB::table('healthcoach_clients')->where('client_id', $user->id)->delete();
            DB::table('group_users')->where('user_id', $user->id)->delete();
            DB::table('html_questionnaire_users')->where('user_id', $user->id)->delete();
            DB::table('investigations')->where('client_id', $user->id)->delete();
            DB::table('notifications')->where('user_id', $user->id)->delete();
            DB::table('package_group_users')->where('user_id', $user->id)->delete();
            DB::table('package_users')->where('user_id', $user->id)->delete();
            DB::table('plan_users')->where('user_id', $user->id)->delete();
            DB::table('prescriptions')->where('client_id', $user->id)->delete();
            DB::table('questionnaire_users')->where('user_id', $user->id)->delete();
            DB::table('recipe_users')->where('user_id', $user->id)->delete();
            DB::table('reminders')->where('user_id', $user->id)->delete();
            DB::table('user_details')->where('user_id', $user->id)->delete();
            DB::table('user_videos')->where('user_id', $user->id)->delete();
            DB::table('role_user')->where('user_id', $user->id)->delete();

            DB::table('workflow_messages')->where('client_id', $user->id)->delete();
            DB::table('workflow_schedules')->where('client_id', $user->id)->delete();
            $user->delete();
            return response()->json(['success' => true, 'message' => 'user deleted successfully']);
        }
        return response()->json(['success' => false, 'message' => 'user not found in storage']);
    }

    public function dhlist(Request $request, $id)
    {
        if ($request->has('alliance_id')) {
            $alliance = User::find($id);
            $userIds = DiagnosticheadAlliance::where('ap_id', $id)->pluck('dh_id');
            $diagnosticpartners = User::whereIn('users.id', $userIds)
                ->where('role_id', 17)
                ->join('diagnostichead_clients', 'users.id', '=', 'diagnostichead_clients.dh_id')
                ->join('user_details', 'users.id', '=', 'user_details.user_id')
                ->select('users.name', 'users.email', 'users.phone', 'users.id as user_id', 'diagnostichead_clients.id', 'user_details.company_name as company_name')->get();

        } else{   
            $client = User::find($id);
            $userIds = DiagnosticheadClient::where('client_id', $id)->pluck('dh_id');
            $diagnosticpartners = User::whereIn('users.id', $userIds)
                ->where('role_id', 17)
                ->join('diagnostichead_clients', 'users.id', '=', 'diagnostichead_clients.dh_id')
                ->join('user_details', 'users.id', '=', 'user_details.user_id')
                ->select('users.name', 'users.email', 'users.phone', 'users.id as user_id', 'diagnostichead_clients.id', 'user_details.company_name as company_name')->get();
        }

        return response()->json(['success' => true, 'diagnosticpartners' => $diagnosticpartners, 'name' => $client->name]);
    }

    public function instructions()
    {
        $instructions = DB::table('instructions')->select('title as label', 'id')->get();
        return response()->json(['success' => true, 'instructions' => $instructions]);
    }

    public function show($id)
    {
        $user = User::find($id);
        return response()->json(['success' => true, 'user' => $user]);
    }

    public function cancel_referral(Request $request)
    {
        $client_id = (int) auth()->user()->id;
        $referred_to = (int) $request->referred_to;

        ClientReferral::where([
            'client_id' => $client_id,
            'referred_to' => $referred_to
        ])->update(['status' => 'decline']);

        return response()->json(['success' => true, 'message' => 'Appointment cancelled successfully!']);
    }

}
