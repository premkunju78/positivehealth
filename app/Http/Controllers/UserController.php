<?php

namespace App\Http\Controllers;


use App\Models\AlianClient;
use App\Models\MarketingheadAlliancepartner;
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
use App\Models\AlianConsultant;
use App\Models\AlianLeagueConsultant;
use App\Models\FavoritesConsultants;
use App\Models\Role;
use Illuminate\Notifications\Messages\MailMessage;
use Mail;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->filters ?? false;
        $client = $request->client_id ?? false;
        $list = $request->list ?? false;
        $hide_consultants = $request->hide_consultants ?? false;
        $consultant_id = $request->consultant_id ?? false;
        if ($filters && gettype($filters) === 'string') {
            $filters = json_decode($filters, true);
        }
        $roles = Role::where('is_consultant', '1')->pluck('id');
        $is_current_user_consultant = Role::where('id', auth()->user()->role_id)->value('is_consultant');
        $users = User::join('roles', 'roles.id', '=', 'users.role_id')
            ->join('user_details', 'user_details.user_id', '=', 'users.id')
            ->select(
                'users.*',
                'user_details.specialization',
                'user_details.specialized_in',
                'user_details.experience',
                'user_details.authorization',
                'user_details.is_upgraded',
                'user_details.hide_consultants',
                DB::raw("CONCAT(user_details.address,', ', user_details.city) as location"),
                'roles.name as role'
            )->whereIn('users.role_id', $roles)
            ->when($filters && !empty($filters), function ($q) use ($filters, $client, $is_current_user_consultant) {
                if (isset($filters['specialization'])) {
                    $q->where('user_details.specialization', $filters['specialization']);
                }
                if (isset($filters['specialized_in'])) {
                    $q->where('user_details.specialized_in', $filters['specialized_in']);
                }
                if (isset($filters['role'])) {
                    $q->where('users.role_id', $filters['role']);
                }
                if (isset($filters['name'])) {
                    $q->where('users.name', 'LIKE', "%" . $filters['name'] . "%");
                }
                if (isset($filters['authorization'])) {
                    $q->where('user_details.authorization', $filters['authorization']);
                }
                if (isset($filters['authorization'])) {
                    $q->where('user_details.authorization', $filters['authorization']);
                }
                if (isset($filters['is_upgraded'])) {
                    $q->where('user_details.is_upgraded', $filters['is_upgraded']);
                }
                $q->where('users.id', '!=', auth()->user()->id);
            })
            ->when(auth()->user()->role_id === 10 || auth()->user()->role_id === 11 || auth()->user()->role_id === 2 || $is_current_user_consultant || $list, function ($q) use($is_current_user_consultant, $filters) {

                $q->where('user_details.is_upgraded', 1);
                
                if (auth()->user()->role_id === 2) {

                    $consultant_ids = AlianLeagueConsultant::where('aliance_id', auth()->user()->id)->value('consultant_ids');                    
                    $consultant_ids = unserialize($consultant_ids);

                    if (empty($consultant_ids)) {
                        $consultant_ids = ['no'];
                    }

                    $q->whereIn('users.id', $consultant_ids);

                }

                if ($is_current_user_consultant && $filters == false) {

                    $alliance_pid = AlianConsultant::where('consultant_id', auth()->user()->id)
                    ->value('aliance_id');

                    $consultant_ids = AlianLeagueConsultant::where('aliance_id', $alliance_pid)->value('consultant_ids');                    
                    $consultant_ids = unserialize($consultant_ids);

                    if (empty($consultant_ids)) {
                        $consultant_ids = ['no'];
                    }

                    $q->whereIn('users.id', $consultant_ids);
                }

                // ->leftJoin('alian_consultants', 'alian_consultants.consultant_id', '=', 'users.id')
                // ->selectRaw("IF(alian_consultants.consultant_id, 'true' , 'false') as checked")
                // ->addSelect('alian_consultants.id as alian_consultant_id','alian_consultants.aliance_id as alian_partner_id');
            })
            ->when(auth()->user()->role_id === 1 || $list, function ($q) {
                $q->leftJoin('alian_consultants', 'alian_consultants.consultant_id', '=', 'users.id')
                    ->selectRaw("IF(alian_consultants.consultant_id, 'true' , 'false') as checked")
                    ->addSelect('alian_consultants.id as alian_consultant_id', 'alian_consultants.aliance_id as alian_partner_id');
            })
            ->when(auth()->user()->role_id === 3, function ($q) {
                $q->orWhere(function ($q) {
                    $q->where('user_details.is_upgraded', true);
                });
                // $partners = AlianClient::where('client_id', auth()->user()->id)->pluck('aliance_id')->toArray();
                // $q->join('alian_consultants','alian_consultants.consultant_id','=','users.id')
                // ->whereIn('alian_consultants.aliance_id',  $partners)
                // ->distinct('alian_consultants.consultant_id');
            })->when($hide_consultants, function ($q) use ($hide_consultants) {
                $q->whereNotIn('users.id', json_decode($hide_consultants,TRUE));
            })->when($consultant_id, function ($q) use ($consultant_id, $is_current_user_consultant) {
                if (!$is_current_user_consultant) {
                    $q->where('users.id', '!=',  $consultant_id);
                }
            })
            ->leftJoin('alian_consultants as ap', 'ap.consultant_id', '=', 'users.id')
            ->leftJoin('user_details as ap_detail', 'ap_detail.user_id', '=', 'ap.aliance_id')
            ->addSelect(DB::raw("CONCAT(ap_detail.company_name) as linked_ap")) 
            ->orderBy('users.name', 'asc');

        $u1 = clone $users;
        return response()->json([
            'users' =>  $u1->limit($request->per_page)->offset(($request->page - 1) * $request->per_page)->get(),
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
            ->when($role === 3, function ($q) {
                $q->leftJoin('alian_client', 'users.id', '=', 'alian_client.client_id')
                    ->leftJoin('client_consultants', 'client_consultants.client_id', '=', 'users.id')
                    ->addSelect('alian_client.aliance_id as alliance_partner', 'client_consultants.consultant_id');
            })
            ->when($role >= 2, function ($q) {

                $q->leftJoin('user_details', 'user_details.user_id', '=', 'users.id')
                    ->addSelect(
                        'user_details.*',
                        'user_details.id as user_detail_id',
                        'users.role_id as role',
                        'users.id'
                    );
            })->find($id);
        $user->dob = date('d-m-Y', strtotime($user->detail->dob));

        $digits = 4;
        $referral_code = 'REF' . trim(pow(10, $digits - 1), pow(10, $digits) - 1) . base64_encode($user->id);

        $user->referralcode = $referral_code;

        if ($slot) {
            $availability =  DB::table('availabilities')->where('user_id', $id)->select('start', 'end', 'frequency')->get();

            $slots = Helpers::getSlots($id, $date, 'h:i a');

            return response()->json([
                'success' => true,
                'user' => $user,
                'availability' => $availability,
                'slots' => $slots
            ]);
        }

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }


    public function getSlots(int $id, $date)
    {
        $bookings = Booking::whereDate('timeslot_from', $date)
            ->where('user_id', $id)->get()->toArray();

        $availabilities = Availability::where('user_id', $id)->whereDate('date', $date)->get();

        $no_of_slots = $availabilities->count();

        $slots = [];

        if ($no_of_slots > 0) {

            $from_val = $to_val = [];


            $currentTime = date('Y-m-d H:i');

            foreach ($availabilities as $k => $availability) {


                $frequency = $availability->frequency;

                $startTime = date('H:i', strtotime($availability->start));

                $endTime = date('H:i', strtotime($availability->end));

                $startDateTime = date('Y-m-d H:i', strtotime($date . " " . $startTime));

                $endDateTime = date('Y-m-d H:i', strtotime($date . " " . $endTime));

                if ($currentTime >  $endDateTime) {
                    return [];
                }

                if ($currentTime > $startDateTime) {
                    if (date('i', strtotime($currentTime)) > 30) {
                        $startTime = date('H', strtotime($currentTime)) + 1 . ":00";
                    } else {
                        $startTime = date('H', strtotime($currentTime)) . ":30";
                    }
                }

                $startDateTime = date('Y-m-d H:i', strtotime($date . " " . $startTime));

                try {
                    while ($startDateTime < $endDateTime) {

                        $newStart = date('Y-m-d H:i', strtotime("+ $frequency minutes", strtotime($startDateTime)));
                        $available = true;
                        $startTime =  date('H:i', strtotime($startDateTime));

                        if (count($bookings)) {
                            foreach ($bookings as $key => $booking) {
                                if (date('H:i', strtotime($booking['timeslot_from'])) === $startTime) {
                                    $available = false;
                                    unset($bookings[$key]);
                                    break;
                                }
                            }
                        }
                        if ($available) {
                            $slots[] = [
                                'from' => date('h:i a', strtotime($startDateTime)),
                                'to' => date('h:i a', strtotime($newStart)),
                            ];
                        }

                        $startDateTime = $newStart;
                    }
                } catch (\Exception $e) {
                    \Log::info('Exception while creating slots', ['excetion' => $e]);
                    return $slots;
                }
            }
        }
        return $slots;
    }

    public function list()
    {
        $usersIds = null;
        $upgraded = request('upgraded') ?? false;
        $video = request('video') ?? null;
        $group = request('group') ?? null;
        $roles = request('roles') ?? null;
        $client = request('client') ?? null;
        $incharge = request('incharge') ?? null;
        $internal = request('internal') ?? null;
        $consultant_id = request('consultant_id') ?? null;
        if ($video) {
            $usersIds = UserVideo::where('video_id', $video)->pluck('user_id')->toArray();
        }
        if ($group) {
            $usersIds = GroupUser::where('group_id', $group)->pluck('user_id')->toArray();
        }
        if ($client) {
            $usersIds = ClientConsultant::where('client_id', $client)->pluck('consultant_id')->toArray();
        }
        if ($incharge) {
            $incharge = User::find($incharge);
            $roles = array($incharge->role_id);
            $usersIds = array($incharge->id);
        }

        $consultant_roles = Role::where('is_consultant', '1')->pluck('id');

        $users = User::join('user_details', 'user_details.user_id', '=', 'users.id')->where('users.status', 1)->whereIn('users.role_id', $consultant_roles)
            ->when($usersIds, function ($q) use ($usersIds) {
                $q->whereNotIn('users.id', $usersIds);
            })
            ->when($roles, function ($q) use ($roles) {
                $q->whereIn('users.role_id', $roles);
            })
            ->when($internal, function ($q) {
                $q->where('user_details.authorization', 'Internal');
            })
            ->when($consultant_id, function ($q) use ($consultant_id) {
                $q->where('users.id', '!=', $consultant_id);
            })
            ->when(((auth()->user()->role_id === 10 || auth()->user()->role_id === 11) && $internal == null) || $upgraded, function ($q) {
                $q->where('user_details.is_upgraded', 1);
            })->select('users.id', 'users.name as label')->get();
        return response()->json(['success' => true, 'users' => $users]);
    }

    public function inchargelist(Request $request)
    {
        $role = $request->role_id ?? null;
        $usersIds = null;
        $consultant_roles = Role::where('is_consultant', '1')->pluck('id');
        $users = User::where('users.status', 1)->where('user_details.incharge', '1')->whereIn('users.role_id', $consultant_roles)
            ->when($usersIds, function ($q) use ($usersIds) {
                $q->whereNotIn('users.id', $usersIds);
            })->when($role, function ($q) use ($role) {
                $q->where('users.role_id', $role);
            })->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->select('users.id', 'users.name as label')->get();
        return response()->json(['success' => true, 'users' => $users]);
    }

    public function clientList()
    {
        $clients = User::when(auth()->user()->role_id != 1, function ($q) {
            $userIds = ClientConsultant::where('consultant_id', auth()->user()->id)->pluck('client_id');
            $q->whereIn('id', $userIds);
        })->where('role_id', 3)->select('name as label', 'id')->get();

        return response()->json(['success' => true, 'clients' => $clients]);
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
            'role' => 'required',
            'specialization' => 'required',
            'authorization' => 'required',
            'specialized_in' => 'required',
            'cost_percent' => 'required',
            'address' => 'nullable',
            'city' => 'nullable',
            'pincode' => 'nullable',
            'logo' => 'nullable',
            'signature_img' => 'nullable',
            'banner_img' => 'nullable',
            'dob' => 'nullable',
            'gender' => 'nullable',
            'occupation' => 'nullable',
            'guard_mob' => 'nullable',
            'state' => 'nullable',
            'company_name' => 'nullable',
            'contact_person' => 'nullable',
            'company_contact' => 'nullable',
            'website_name' => 'nullable',
            'url' => 'nullable',
            'group' => 'nullable',
            'type' => 'nullable',
            'welcome_message' => 'nullable',
            'cost' => 'nullable',
            'cost_percent' => 'nullable',
            'specialization' => 'nullable',
            'authorization' => 'nullable',
            'specialized_in' => 'nullable',
            'qualification' => 'nullable',
            'certificates' => 'nullable',
            'bank_name' => 'nullable',
            'account_no' => 'nullable',
            'ifsc_code' => 'nullable',
            'cancelled_cheque' => 'nullable',
            'bio' => 'nullable',
            'clinic_name' => 'nullable',
        ];
    }

    public function store(Request $request, User $user)
    {

        $request->validate($this->rules(), [
            'specialized_in.required' => 'specializaion field is required',
            'specialization.required' => 'role type field is required',
            'role.required' => 'sub role field is required',
            'cost_percent.required' => 'precentage sharing field is required'
        ]);
        DB::beginTransaction();
        try {

            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->name = $request->first_name . ' ' . $request->last_name;
            $user->email = $request->email;
            $user->role_id = $request->role;
            $user->phone = $request->phone;
            $user->save();

            $data = $request->all();
            $data['skills'] = json_decode($request->skills, true);
            $data['languages'] = json_decode($request->languages, true);

            $data['certificates'] = [];
            $data['user_id'] = $user->id;
            $data =  Helpers::storeFiles($request, $data);

            $data['dob'] = (!empty($request->dob)) ? date('Y-m-d', strtotime($request->dob)) : NULL;
            $data['notes'] = $request->notes ?? "";

            UserDetail::create($data);

            $role_user = new RoleUser;
            $role_user->user_id = $user->id;
            $role_user->role_id = $request->role;
            $role_user->save();



            $resetUrl = URL::signedRoute('generate-password', ['user' => $user->id]);
            $user->notify(new NewAccountCreated($resetUrl));

            DB::commit();

            return response()->json(['success' => true, 'message' => "User {$user->name} created successfully"]);
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return response()->json(['success' => false, 'message' => "Something went wrong please try after some time"]);
        }
    }

    public function update_availability_status(Request $request, int $id) {
        $request->validate([
            'city' => 'required',
            'status' => 'required',
        ]);

        $city = $request->input('city');
        $status = $request->input('status');

        $available_cities = UserDetail::where('user_id', $id)->value('available_cities');
        
        foreach ($available_cities as $key => $location) {            
            if ($location['city'] == $city) {
                $location['status'] = $location['status'] === 'active' ? 'inactive' : 'active';
            }

            $available_cities[$key]['status'] = $location['status'];
        }

        UserDetail::where('user_id', $id)->update(['available_cities' => $available_cities]);

        return response()->json(['success' => true, 'message' => "Availability updated successfully"]);
    }

    public function update(Request $request, int $id)
    {
        $user = User::find($id);
        if ($user) {
            $request->validate($this->rules($id), [
                'specialized_in.required' => 'specializaion field is required',
                'specialization.required' => 'role type field is required',
                'role.required' => 'sub role field is required',
                'cost_percent.required' => 'precentage sharing field is required'
            ]);
            DB::beginTransaction();
            try {
                $user->first_name = $request->first_name;
                $user->last_name = $request->last_name;
                $user->name = $request->first_name . ' ' . $request->last_name;
                $user->email =  $request->email;
                $user->role_id = $request->role;
                $user->phone = $request->phone;
                $user->update();

                $data = $request->all();

                $data['skills'] = json_decode($request->skills, true);
                $data['languages'] = json_decode($request->languages, true);

                $userDetail = UserDetail::where('user_id', $user->id)->first();
                $data['dob'] = (!empty($request->dob)) ? date('Y-m-d', strtotime($request->dob)) : NULL;
                $data['cancelled_cheque'] = $userDetail->cancelled_cheque;
                $data['certificates'] = $userDetail->certificates;
                $data['signature_img'] = $userDetail->signature_img;
                $data['logo'] = $userDetail->logo;
                $data['notes'] = $request->notes ?? "";
                $data = Helpers::storeFiles($request, $data);

                // $data['dob'] = $data['dob'] === 'null'  ? null : $data['dob'];

                $userDetail->update($data);

                $role_user = RoleUser::where('user_id', $user->id)->first();
                $role_user->role_id = $user->role_id;
                $role_user->update();

                DB::commit();
                return response()->json(['success' => true, 'message' => "User {$user->name} updated successfully"]);
            } catch (\Exception $e) {
                DB::rollback();
                dd($e);
                return response()->json(['success' => false, 'message' => "Something went wrong please try after some time"]);
            }
        }
    }



    public function checkuserpassword(Request $request)
    {
        $user = User::find($request->user);
        if ($user) {
            if (!$user->password) {
                return response()->json(['type' => 'success',   'message' => '']);
            } else {
                return response()->json(['user' => null, 'type' => 'danger', 'message' => 'link expired']);
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

    public function videos(int $id)
    {
        $existingVideos = UserVideo::where('user_id', $id)->pluck('video_id')->toArray();

        $videos = [];
        $total = 0;

        $total = $this->fetchVideos($videos, 1, $existingVideos);
        if ($total > 100) { //since max limit is 100 for pagination
            $pages = ($total / 100) > 0 ?  ceil($total / 100) : 0;
            $i = 2;
            while ($i <= $pages) {
                $this->fetchVideos($videos, $i, $existingVideos);
                $i++;
            }
        }

        return response()->json([
            'success' => true,
            'videos' => $videos
        ]);
    }

    private function fetchVideos(&$videos, $page = 1, $existingVideos)
    {
        $response = Vimeo::request('/me/videos', ['per_page' => 100, 'page' => $page], 'GET');
        if (isset($response['body']) && isset($response['body']['data'])) {
            foreach ($response['body']['data'] as $d) {
                $id = explode('/', $d['uri'])[2];
                if (!in_array($id, $existingVideos)) {
                    array_push($videos, ['label' => $d['name'], 'id' => explode('/', $d['uri'])[2]]);
                }
            }
        }
        return $response['body']['total'];
    }

    public function specialized()
    {
        $specialization = request('specialization') ?? null;
        $specialized = User::whereNotIn('role_id', [2, 3])->distinct('user_details.specialized_in')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->when($specialization, function ($q) use ($specialization) {
                $q->where('user_details.specialization', $specialization);
            })
            ->select('users.*', 'user_details.specialization', 'user_details.specialized_in')->pluck('user_details.specialized_in');
        return response()->json([
            'success' => true,
            'specialized' => $specialized
        ]);
    }

    public function refferConsultant(Request $request)
    {
        try {
            $mapping = ClientConsultant::where([
                'client_id' => $request->client_id,
                'consultant_id' => $request->consultant_id
            ])->firstOrNew();

            $mapping->client_id = $request->client_id;
            $mapping->consultant_id = $request->consultant_id;
            $mapping->save();

            $referral = new ClientReferral;
            $referral->referred_by = auth()->user()->id;
            $referral->referred_to = $request->consultant_id;
            $referral->client_id = $request->client_id;
            $referral->notes = $request->notes;
            $referral->date = date('Y-m-d');
            $referral->save();

            $client = User::find($referral->client_id);

            Notification::create([
                'user_id' => $referral->client_id,
                'message' => "You have been referred to {$request->consultant_name} by " . auth()->user()->name,
                'meta' => [
                    'consultant_id' => $request->consultant_id,
                    'role_id' => $request->consultant_role
                ]
            ]);

            Notification::create([
                'user_id' => $request->consultant_id,
                'message' => "You have been referred to {$client->name} by " . auth()->user()->name,
                'meta' => [
                    'client_id' => $referral->client_id,
                    'role_id' => 3
                ]
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Consultant referred successfully'
            ]);
        } catch (\Exception $e) {
            DB::rolback();

            return response()->json([
                'success' => false,
                'message' => 'Error 500 . something went wrong, please try again'
            ]);
        }
    }

    public function upgrade(Request $request, int $id)
    {
        $user = UserDetail::where('user_id', $id)->first();
        
        if ($user) {
            $user->is_upgraded = !$user->is_upgraded;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Consultant access updated successfully'
            ]);
        }
        return response()->json([
            'success' => true,
            'message' => 'Consultant details not found in storage'
        ]);
    }


    public function paymentDetails(Request $request)
    {
        $users = Booking::join('users as consultant', 'consultant.id', '=', 'bookings.user_id')
            ->join('users as client', 'client.id', '=', 'bookings.booked_by')
            ->whereNotNull('bookings.cost')
            ->orderBy('bookings.id', 'desc')
            ->select(
                'consultant.name as consultant',
                'client.name as client',
                'bookings.cost',
                'bookings.razorpay_payment_id',
                'bookings.transaction_date'
            );

        if (auth()->user()->role_id == 15) {
            $alliance_partners = MarketingheadAlliancepartner::where('mh_id', auth()->user()->id)->pluck('id');
            $consultants = AlianConsultant::whereIn('aliance_id', $alliance_partners)->pluck('consultant_id');
            $users->whereIn('consultant.in', $consultants);
        }

        $u1 = clone $users;
        return response()->json([
            'users' =>  $u1->limit($request->per_page)->offset(($request->page - 1) * $request->per_page)->get(),
            'page' => $request->page,
            'total' => $users->count(),
            'per_page' => $request->per_page
        ]);
    }

    public function allianassignment(Request $request)
    {
        $request->validate(
            ['consultant' => 'required', 'alliance_partner' => 'required']
        );
        DB::beginTransaction();
        try {
            AlianConsultant::where('consultant_id', $request->consultant)->delete();
            AlianConsultant::insert([
                'consultant_id' => $request->consultant,
                'aliance_id' =>  $request->alliance_partner
            ]);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Mapping updated successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return response(['success' => false, 'message' => 'something went wrong, Please try after some time']);
        }
    }
    public function destroy(int $id)
    {
        $user = User::find($id);
        if ($user) {
            DB::table('alian_consultants')->where('consultant_id', $user->id)->delete();
            DB::table('availabilities')->where('user_id', $user->id)->delete();
            DB::table('bookings')->where('user_id', $user->id)->delete();
            DB::table('case_sheets')->where('user_id', $user->id)->delete();
            DB::table('chat_messages')->where('from_id', $user->id)->delete();
            DB::table('chat_messages')->where('to_id', $user->id)->delete();
            DB::table('client_consultants')->where('consultant_id', $user->id)->delete();
            DB::table('client_referrals')->where('referred_by', $user->id)->delete();
            DB::table('client_referrals')->where('referred_to', $user->id)->delete();
            DB::table('diet_plans')->where('creator', $user->id)->delete();
            DB::table('discount_coupons')->where('user_id', $user->id)->delete();
            DB::table('discount_users')->where('user_id', $user->id)->delete();
            DB::table('enquiries')->where('user_id', $user->id)->delete();
            DB::table('feedbacks')->where('user_id', $user->id)->delete();
            DB::table('group_users')->where('user_id', $user->id)->delete();
            DB::table('investigations')->where('user_id', $user->id)->delete();
            DB::table('leads')->where('user_id', $user->id)->delete();
            DB::table('notifications')->where('user_id', $user->id)->delete();
            DB::table('package_group_users')->where('user_id', $user->id)->delete();
            DB::table('package_users')->where('user_id', $user->id)->delete();
            DB::table('plan_users')->where('user_id', $user->id)->delete();
            DB::table('prescriptions')->where('user_id', $user->id)->delete();
            DB::table('recipes')->where('creator', $user->id)->delete();
            DB::table('recipe_users')->where('user_id', $user->id)->delete();
            DB::table('role_user')->where('user_id', $user->id)->delete();
            DB::table('user_details')->where('user_id', $user->id)->delete();
            DB::table('workflow_schedules')->where('user_id', $user->id)->delete();
            $user->delete();
            return response()->json(['success' => true, 'message' => 'user deleted successfully']);
        }
        return response()->json(['success' => false, 'message' => 'user not found in storage']);
    }

    public function removeconsultant(Request $request)
    {
        $user = UserDetail::where('user_id', $request->consultant_id)->first();
        $user->hide_consultants = $request->consultant;
        $user->save();
        return response()->json(['success' => true, 'message' => 'Data updated successfully']);
    }

    public function check_upgrade ($user_id){
        $is_upgraded = UserDetail::where('user_id', $user_id)->value('is_upgraded');
        $is_current_user_consultant = Role::where('id', auth()->user()->role_id)->value('is_consultant');
        
        return response()->json(['success' => true, 'message' => 'User update statue', 'is_upgraded' => $is_upgraded]);   
    }

    public function get_users_and_roles(Request $request)
    {
        $roles =  Role::select('id', 'label')
        ->where('status', '1')
        ->where('id', '!=', '1')
        ->orderBy('label', 'ASC')
        ->get();

        if($request->role_id) {
            $users = User::select('id', 'name as label')
            ->where('role_id', $request->role_id)
            ->orderBy('name', 'ASC')->get();
        }

        return response()->json(['success' => true, 'user' => [
            'role_list' => $roles,
            'user_list' => @$users   
        ]]);
    }

    public function consultants_assign_to_ap(Request $request, $aliance_id) {
        $consultant_ids = $request->input('consultant_ids');
        $consultant_ids = serialize($consultant_ids);

        $is_exists = AlianLeagueConsultant::where('aliance_id', $aliance_id)->first();

        if ($is_exists) {
            $is_exists->consultant_ids = $consultant_ids;
            $is_exists->save();
        }else{
            AlianLeagueConsultant::insert([
                'aliance_id' => $aliance_id,
                'consultant_ids' => $consultant_ids
            ]);                
        }

        return response()
        ->json(['success' => true, 'message' => 'League Consultant Added Successfully!']);
    }

    public function getFavoritesConsultants(Request $request) {
        $consultant_ids = FavoritesConsultants::where('favoroute_of', auth()->user()->id)->value('consultant_ids');

        return response()->json(['success' => true, 'user' => [
            'consultant_ids' => $consultant_ids,
        ]]);                    
    }

    public function makeFavoritesConsultants(Request $request) {
        $consultant_ids = $request->input('consultant_ids');        
        $favoroute_of = $request->input('user_id');
        
        if (FavoritesConsultants::where('favoroute_of', $favoroute_of)->exists()) {
            FavoritesConsultants::where('favoroute_of', $favoroute_of)->update(['consultant_ids' => $consultant_ids]);
        }else{
            FavoritesConsultants::create([
                'favoroute_of' => $favoroute_of,
                'consultant_ids' => $consultant_ids
            ]);
        }

        return response()
        ->json(['success' => true, 'message' => 'Consultants saved successfully!']);

    }
}
