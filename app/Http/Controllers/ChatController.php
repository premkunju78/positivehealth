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
use App\Models\HealthcoachLead;


class ChatController extends Controller
{
    public function rooms(Request $request)
    {
        $id = auth()->user()->id;
        $role = auth()->user()->role_id;
        $users = User::select('users.*')
            ->when($role == 10, function ($q) {
                $q->where('users.role_id', 2);
            })->when($role == 2, function ($q) {
                $q->where('users.role_id', 5);
            })->when($role == 11, function ($q) {
                $hc_clients = DB::table('healthcoach_clients')->where('hc_id', auth()->user()->id)->pluck('client_id');
                $q->where('users.role_id', 3)->whereIn('id', $hc_clients);
            })->when($role == 3, function ($q) {
                $client_hcs = DB::table('healthcoach_clients')->where('client_id', auth()->user()->id)->pluck('hc_id');
                $q->where('users.role_id', 11)->whereIn('id', $client_hcs);
            })->get();
        foreach ($users as $key => $user) {
            $rooms[] = array(
                'roomId' => $user->id,
                'roomName' => $user->name,
                'avatar' => 'assets/imgs/people.png',
                'unreadCount' => 0,
                'index' => 0,
                'lastMessage' => array(),
                'users' => array(
                    array(
                        '_id' => auth()->user()->id,
                        'username' => auth()->user()->name,
                        'avatar' => 'assets/imgs/doe.png',
                        'status' => array(
                            'state' => 'online',
                            'lastChanged' => 'today, 14:30'
                        )
                    ), array(
                        '_id' => $user->id,
                        'username' => $user->name,
                        'avatar' => 'assets/imgs/doe.png',
                        'status' => array(
                            'state' => 'online',
                            'lastChanged' => 'today, 14:30'
                        )
                    )
                )
            );
        }
        $room = $rooms[0];
        $messages = DB::table('chat_messages')->where(function ($query) use ($room) {
            $query->where('from_id', '=', $room['roomId']);
            $query->where('to_id', '=', auth()->user()->id);
        })->orWhere(function ($query) use ($room) {
            $query->where('to_id', '=', $room['roomId']);
            $query->where('from_id', '=', auth()->user()->id);
        })->get();
        $messagedata = array();
        foreach ($messages as $index => $message) {
            $user = User::find($message->from_id);
            $messagedata[] = array(
                '_id' => $message->id,
                'indexId' => $index,
                'content' => $message->content,
                'senderId' => $message->from_id,
                'username' => $user->name,
                'avatar' => 'assets/imgs/doe.png',
                'date' => date('d M', strtotime($message->created_at)),
                'timestamp' => date('H:i', strtotime($message->created_at)),
                'system' => false,
                'saved' => true,
                'distributed' => true,
                'seen' => ($message->seen == '1') ? true : false,
                'deleted' => false,
                'failure' => true,
                'disableActions' => false,
                'disableReactions' => false,
            );
        }
        return response()->json([
            'rooms' => $rooms,
            'messages' => $messagedata
        ]);
    }

    public function sendmessage(Request $request)
    {
        $message_data = array(
            'from_id' => auth()->user()->id,
            'to_id' => $request->roomId,
            'content' => $request->message['content'],
        );
        DB::table('chat_messages')->insert($message_data);
        return response()->json([
            'success' => true,
        ]);
    }

    public function messages($id)
    {
        $messages = DB::table('chat_messages')->where(function ($query) use ($id) {
            $query->where('from_id', '=', $id);
            $query->where('to_id', '=', auth()->user()->id);
        })->orWhere(function ($query) use ($id) {
            $query->where('to_id', '=', $id);
            $query->where('from_id', '=', auth()->user()->id);
        })->get();
        $messagedata = array();
        foreach ($messages as $index => $message) {
            $user = User::find($message->from_id);
            $messagedata[] = array(
                '_id' => $message->id,
                'indexId' => $index,
                'content' => $message->content,
                'senderId' => $message->from_id,
                'username' => $user->name,
                'avatar' => 'assets/imgs/doe.png',
                'date' => date('d M', strtotime($message->created_at)),
                'timestamp' => date('H:i', strtotime($message->created_at)),
                'system' => false,
                'saved' => true,
                'distributed' => true,
                'seen' => ($message->seen == '1') ? true : false,
                'deleted' => false,
                'failure' => false,
                'disableActions' => false,
                'disableReactions' => false,
            );
        }
        return response()->json([
            'messages' => $messagedata
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
            'healthcoach' => $user
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
        $users = User::where('users.status', 1)->where('users.role_id', '=', 6)
            ->when($usersIds, function ($q) use ($usersIds) {
                $q->whereNotIn('users.id', $usersIds);
            })->select('users.id', 'users.name as label')->get();
        $healthcoaches = User::where('users.status', 1)->where('users.role_id', '=', 6)
            ->when($usersIds, function ($q) use ($usersIds) {
                $q->whereNotIn('users.id', $usersIds);
            })->join('user_details', 'users.id', '=', 'user_details.user_id')->where('user_details.cisf', '0')->select('users.id', 'users.name as label')->get();
        $cisfs = User::where('users.status', 1)->where('users.role_id', '=', 6)
            ->when($usersIds, function ($q) use ($usersIds) {
                $q->whereNotIn('users.id', $usersIds);
            })->join('user_details', 'users.id', '=', 'user_details.user_id')->where('user_details.cisf', '1')->select('users.id', 'users.name as label')->get();
        return response()->json(['success' => true, 'users' => $users, 'healthcoaches' => $healthcoaches, 'cisfs' => $cisfs]);
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
            $user->role_id = 6;
            $user->phone = $request->phone;
            $user->save();

            $data = $request->all();
            $data['user_id'] = $user->id;
            $data =  Helpers::storeFiles($request, $data);
            UserDetail::create($data);
            $role_user = new RoleUser;
            $role_user->user_id = $user->id;
            $role_user->role_id = 6;
            $role_user->save();
            $resetUrl = URL::signedRoute('generate-password', ['user' => $user->id]);
            $user->notify(new NewAccountCreated($resetUrl));
            DB::commit();
            return response()->json(['success' => true, 'message' => "Healthcoach {$user->name} created successfully"]);
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
                $user->role_id = 6;
                $user->phone = $request->phone;
                $user->update();

                $data = $request->all();

                $userDetail = UserDetail::where('user_id', $user->id)->first();
                $data['dob'] = $request->dob ?? null;
                $data['cancelled_cheque'] = $userDetail->cancelled_cheque;
                $data['idproof'] = $userDetail->idproof;
                $data = Helpers::storeFiles($request, $data);

                // $data['dob'] = $data['dob'] === 'null'  ? null : $data['dob'];

                $userDetail->update($data);

                $role_user = RoleUser::where('user_id', $user->id)->first();
                $role_user->role_id = $user->role_id;
                $role_user->update();

                DB::commit();
                return response()->json(['success' => true, 'message' => "Healthcoach {$user->name} updated successfully"]);
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


            Notification::create([
                'user_id' => $referral->client_id,
                'message' => "You have been referred to {$request->consultant_name} by " . auth()->user()->name,
                'meta' => [
                    'consultant_id' => $request->consultant_id,
                    'role_id' => $request->consultant_role
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

    public function upgrade(int $id)
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

        $u1 = clone $users;
        return response()->json([
            'users' =>  $u1->limit($request->per_page)->offset(($request->page - 1) * $request->per_page)->get(),
            'page' => $request->page,
            'total' => $users->count(),
            'per_page' => $request->per_page
        ]);
    }

    public function storeLeadAssignment(Request $request)
    {
        $request->validate(
            ['leads.*' => 'required', 'healthcoach' => 'required']
        );

        DB::beginTransaction();
        try {
            foreach ($request->leads as $lead) {
                $data[] = [
                    'healthcoach_id' => $request->healthcoach,
                    'lead_id' => $lead
                ];
            }
            HealthcoachLead::insert($data);
            DB::commit();
            return response(['success' => true, 'message' => 'Lead assigned successfully']);
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return response(['success' => false, 'message' => 'something went wrong, Please try after some time']);
        }
    }
    public function leads($id)
    {
        $leads = HealthcoachLead::join('leads', 'leads.id', '=', 'healthcoach_leads.lead_id')
            ->select(
                'leads.*',
                'healthcoach_leads.id as healthcoach_lead_id',
            )
            ->where('healthcoach_leads.healthcoach_id', $id)->get();
        return response()->json([
            'leads' => $leads,
        ]);
    }
    public function removeLead($id)
    {
        try {
            $lead = HealthcoachLead::find($id)->delete();
            return response(['success' => true, 'message' => 'Lead removed successfully']);
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return response(['success' => false, 'message' => 'something went wrong, Please try after some time']);
        }
    }
}
