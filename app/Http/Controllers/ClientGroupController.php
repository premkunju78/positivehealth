<?php

namespace App\Http\Controllers;

use App\Models\ClientGroup;
use App\Models\ClientGroupMapping;
use App\Models\ClientGroupUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use App\Models\AlianClient;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\utils\Helpers;
use Session;

class ClientGroupController extends Controller
{
    public function index(Request $request)
    {
        $clientGroupList = ClientGroup::select('client_groups.*', DB::raw("client_groups.no_of_participants - COUNT(client_group_mappings.client_id) as available_slots"))
        ->leftJoin('client_group_mappings', 'client_group_mappings.group_id','=','client_groups.id')
        ->limit($request->per_page)
        ->groupBy('client_groups.id')
        ->offset(($request->page - 1) * $request->per_page)
        ->get();

        $clientGroupList = $clientGroupList ? $clientGroupList->toArray() : [];  
        if (!empty($clientGroupList)) {
            foreach ($clientGroupList as $key => $clientgroup) {
                $clientGroupList[$key]['detail_link'] = URL("/clients/payment/".base64_encode(time())."/" . base64_encode($clientgroup['id']));
            }
        }

        return response()->json([
            'groups' => $clientGroupList,
            'page' => $request->page,
            'total' => ClientGroup::count(),
            'per_page' => $request->per_page
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'unique:client_groups,name'],
            'description' => 'required',
            // 'start_date' => 'required',
            // 'end_date' => 'required',
        ]);

        $group = new ClientGroup();

        $group->name = $request->name;
        $group->description = $request->description;
        $group->group_name = $request->group_name;
        $group->group_type = $request->group_type;
        $group->session_no = $request->session_no;
        $group->no_of_participants = $request->no_of_participants;
        $group->payment_amount = $request->payment_amount;
        $group->session_type = $request->session_type;
        $group->sessions = json_decode($request->sessions);

        if( $request->hasFile('logo') )
        {
            $ext = $request->logo->getClientOriginalExtension();
            if( in_array(strtolower($ext), ['png','svg','jpg','jpeg','gif'])) {
                $group->logo = $request->logo->store('clientgroups/logo');
            }
        }

        $group->save();

        return response()->json(['success' => true, 'message' => 'Client Group created succssfully', 'type' => 'success']);
    }

    public function clientassignment(Request $request)
    {
        if (!empty($request->selected)) {
            try {
                foreach ($request->selected as $client) {
                    ClientGroupMapping::updateOrCreate(
                        [
                            'client_id' => $client,
                            'group_id' => $request->group_id,
                        ],
                        [
                            'client_id' => $client,
                            'group_id' => $request->group_id,
                        ]
                    );
                }
                return response()->json(['success' => true, 'message' => "Client added to group successfully!"]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }
        } else {
            return response()->json(['success' => false, 'message' => "Select atleast 1 client"]);
        }
    }

    public function show($id)
    {
        if (base64_decode($id, true) !== false) {
            $id = base64_decode($id);
        }

        $group = ClientGroup::find($id);
        if ($group) {
            return response()->json(['success' => true, 'group' => $group, 'type' => 'success']);
        }
        return response()->json(['success' => false, 'message' => 'Client group not found in storage', 'type' => 'danger']);
    }

    public function update(Request $request, int $id)
    {
        $request->validate(
            [
                'name' => ['required', 'unique:client_groups,name,' . $id],
                'description' => 'required',
            ]
        );

        $group = ClientGroup::find($id);
        if ($group) {

            $group->name = $request->name;
            $group->description = $request->description;
            $group->group_name = $request->group_name;
            $group->group_type = $request->group_type;
            $group->session_no = $request->session_no;
            $group->no_of_participants = $request->no_of_participants;
            $group->payment_amount = $request->payment_amount;

            if( $request->hasFile('logo') )
            {
                $ext = $request->logo->getClientOriginalExtension();
                if( in_array(strtolower($ext), ['png','svg','jpg','jpeg','gif'])) {
                    $group->logo = $request->logo->store('clientgroups/logo');
                }
            }

            $group->session_type = $request->session_type;
            $group->sessions = json_decode($request->sessions);
            $group->update();

            return response()->json(['success' => true, 'message' => 'Client group updated succesfully', 'type' => 'success']);
        }
        return response()->json(['success' => false, 'message' => 'Client group not found in storage', 'type' => 'danger']);
    }

    public function destroy(int $id)
    {
        $group = ClientGroup::find($id);
        if ($group) {
            ClientGroupMapping::where('group_id', $id)->delete();
            $group->delete();
            return response()->json(['success' => true, 'message' => 'Client group deleted succesfully', 'type' => 'success']);
        }
        return response()->json(['success' => false, 'message' => 'Client group not found in storage', 'type' => 'danger']);
    }


    public function removeMapping(int $id)
    {
        $mapping = ClientGroupMapping::find($id);
        if ($mapping) {
            $mapping->delete();

            return response()->json(['success' => true, 'message' => 'Client group mapping deleted succesfully', 'type' => 'success']);
        }
        return response()->json(['success' => false, 'message' => 'Client group mapping not found in storage', 'type' => 'danger']);
    }

    public function list()
    {
        $groups = ClientGroup::select('client_groups.id', 'client_groups.name as label')->get();
        return response()->json(['success' => true, 'groups' => $groups]);
    }

    public function assignedusers(Request $request){
        $mapping = ClientGroupMapping::where('group_id',$request->group_id)
        ->join('users','users.id','client_group_mappings.client_id')
        ->leftJoin('alian_client', 'alian_client.client_id', '=', 'client_group_mappings.client_id')
        ->leftJoin('users as partner', 'partner.id', '=', 'alian_client.aliance_id')
        ->leftJoin('user_details as alian_details', 'partner.id', '=', 'alian_details.user_id')
        ->select('users.id','users.name as user_name','users.phone','alian_details.company_name as referred_partner','client_group_mappings.id as mapping_id')->get();
        return response()->json(['success' => true, 'users' => $mapping]);
    }

    public function createLink(Request $request)
    {
        if (!$request->has('consultant_id')) {
            return response()->json(['error' => true, 'message' => 'Invalid consultant id']);
        }

        $consultant_id = (int) $request->consultant_id;
        $consultant = User::find($consultant_id);

        $meeting_url = config('services.enablex.domain');
        $frequency = 30;
        $url = config('services.enablex.url') . '/' . config('services.enablex.version');
        $timeslot_from = new DateTime($request->start_time);
        $timeslot_from = $timeslot_from->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode(config('services.enablex.app_id') . ":" . config('services.enablex.app_key')),
        ])->post("$url/rooms", [
            "name" => 'New Group Session',
            "owner_ref" => $consultant->name . '-' . $consultant->id,
            "settings" => [
                "description" => 'New Group Session',
                "mode" => "group",
                "scheduled" => false,
                "adhoc" => true,
                "duration" => (int)$frequency,
                "moderators" => "1",
                "participants" => "1",
                "quality" => "SD",
                "auto_recording" => false,
            ]
        ])->json();

        if (isset($response['room'])) {
            $host_link = $meeting_url . '/' . 'host/' . base64_encode($response['room']['room_id'] . "-" . config('services.enablex.app_id')) . '?name=' . $consultant->name;
        }

        return response()->json(['success' => true, 'message' => 'video link generated successfully', 'data' => ['meeting_link' => $host_link, 'room_id' => $response['room']['room_id']]]);
    }

    public function generatepaymentlink(Request $request)
    {
        $group_id = $request->group_id;
        $rand_string = md5(uniqid($group_id, true));

        return response()->json(['success' => true, 'message' => 'payment link generated successfully', 'data' => ['payment_link' => URL::to('/clients/payment/'.$rand_string.'/' . base64_encode($group_id)) ]]);        
    }

    public function validate_external_client(Request $request)
    {
        $request->validate(
            ['phone' => 'required']
        );

        $digits = 4;
        $otp = rand(pow(10, $digits-1), pow(10, $digits)-1);
        $phone = $request->input('phone');

        $varificationType = User::where('phone', $phone)->exists() ? 'internal' : 'external';

        $ap_endpoint = User::select('alian_details.url as ap_endpoint')
        ->join('alian_client', 'alian_client.client_id', '=', 'users.id')
        ->join('users as alian', 'alian.id', '=', 'alian_client.aliance_id')
        ->join('user_details as alian_details', 'alian_details.user_id', '=', 'alian_client.aliance_id')
        ->where('users.phone', $phone)
        ->first();

        $ap_endpoint = $ap_endpoint ? $ap_endpoint->ap_endpoint : 'healthinfinit';

        $sendMessage = Helpers::sendSms($phone, 'Dear Customer, your OTP is ' . $otp . '. Use this to verify your mobile - Healthinfinit');

        return response()->json([
            'success' => true,
            'otp' => $otp,
            'varification_type' => $varificationType,
            'ap_endpoint' => $ap_endpoint
        ]);

    }

    public function get_next_group_id(Request $request) {
        $next_group_id = ClientGroup::orderBy('id', 'DESC')->value('id') + 1;

        return response()->json([
            'success' => true,
            'next_group_id' => $next_group_id
        ]);
    }


    public function alliangroupassignment(Request $request)
    {
        $request->validate(
            ['group_id' => 'required', 'alliance_partner_id' => 'required', 'alliance_client_id' => 'required']
        );

        $existingClients = ClientGroupMapping::where('group_id', $request->group_id)->count('client_id') + 1;

        if ($existingClients > ClientGroup::find($request->group_id)->no_of_participants) {
            return response(['success' => false, 'message' => 'All slots are full for this group']);
        }

        DB::beginTransaction();

        try {
            ClientGroupMapping::updateOrCreate(
                [
                    'client_id' => $request->alliance_client_id,
                    'group_id' => $request->group_id,
                ],
                [
                    'client_id' => $request->alliance_client_id,
                    'group_id' => $request->group_id,
                ]
            );
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Mapping updated successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response(['success' => false, 'message' => 'something went wrong, Please try after some time']);
        }
    }

}
