<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use App\Models\WorkflowSchedule;
use App\Models\ClientConsultant;
use App\Models\User;
use App\Models\WorkflowMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AppointConfToConsult;
use App\Notifications\AppointConfirmationToClient;
use App\utils\Helpers;
use App\Services\Vi;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use DateTime;
use DateTimeZone;
use Exception;
use DB;

class WorkflowscheduleController extends Controller
{
    public function index(Request $request)
    {
        $date = date('Y-m-d', strtotime($request->date));

        $slots = Helpers::getSlots($request->user, $date);

        return response()->json(['slots' => $slots]);
    }


    public function store(Request $request)
    {
        $request->validate($this->rules());
        $scheduleInfo = $request->all();
        $scheduleInfo['groupid'] = (!empty($request->clients)) ? rand(0, 9999) : null;
        $scheduleInfo['instruction'] = ($request->instruction) ? $request->instruction : null;
        $scheduleInfo['status'] = 'Pending';
        $scheduleInfo['schedule_datettime'] = date('Y-m-d H:i:s', strtotime($request->schedule_time));
        $scheduleInfo['created_by'] = auth()->user()->id;
        if ($request->assignedto == 'self') {
            $scheduleInfo['user_id'] = auth()->user()->id;
        } else {
            $scheduleInfo['user_id'] = $request->user_id;
        }
        if ($request->id) {
            $schedule = WorkflowSchedule::find($request->id);
            $grpid=$schedule->groupid;
            $scheduleInfo['client_id'] = $request->client_id;
            $schedule->update($scheduleInfo);
            $clientids = (!empty($request->clients)) ? explode(',', $request->clients) : [];
            $scheduleids=[];
            $scheduleids[]=$request->id;
            if (!empty($clientids)) {
                foreach ($clientids as $clientid) {
                    $clientschedule = WorkflowSchedule::where('client_id', $clientid)->where('groupid', $grpid)->first();
                    if (!empty($clientschedule)) {
                        $scheduleInfo['client_id'] = $clientid;
                        $clientschedule->update($scheduleInfo);
                        $scheduleids[]=$clientschedule->id;
                    } else {
                        $scheduleInfo['client_id'] = $clientid;
                        $clientschedule = new WorkflowSchedule($scheduleInfo);
                        $clientschedule->save();
                        $scheduleids[]=$clientschedule->id;
                    }
                }
            }
            if(!empty($grpid) && !empty($scheduleids)){
                WorkflowSchedule::where('groupid', $grpid)->whereNotIn('id', $scheduleids)->delete();
            }
        } else {
            $clientids = (!empty($request->clients)) ? explode(',', $request->clients) : [];
            $clientids[] = $request->client_id;
            $clientids = array_unique($clientids);
            foreach ($clientids as $clientid) {
                $scheduleInfo['client_id'] = $clientid;
                $schedule = new WorkflowSchedule($scheduleInfo);
                $schedule->save();
            }
        }
        if ($request->assignedto == 'consultant' || $request->assignedto == 'internalconsultant' || $request->assignedto == 'incharge') {
            $data = [
                'client_id' => $scheduleInfo['client_id'],
                'consultant_id' =>  $scheduleInfo['user_id'],
            ];
            ClientConsultant::updateOrCreate($data, $data);
        }
        return response()->json(['success' => true, 'message' => 'Schedule assigned successfully']);
    }

    protected function rules()
    {
        return [
            'client_id' => 'required',
            'title' => 'required',
            'schedule_time' => 'required',
            'type' => 'required',
            'description' => 'sometimes',
        ];
    }

    public function schedules()
    {
        $id = request('id') ?? auth()->user()->id;
        $past = request('past') ?? false;
        $role = auth()->user()->role_id;
        $schedules = Booking::select('bookings.*')->when(in_array($role, [2, 3]), function ($q) use ($id) {
            $q->where('booked_by',  $id)
                ->join('users', 'users.id', '=', 'bookings.user_id')
                ->addSelect('users.name as name');
        }, function ($q) use ($id) {
            $q->where('user_id', $id);
        })
            ->when($past == 'true', function ($q) {
                $q->whereDate('timeslot_from', '<', date('Y-,m-d'));
            }, function ($q) {
                $q->whereDate('timeslot_from', '>=', date('Y-m-d'));
            });
        return response()->json(['success' => true, 'schedules' => $schedules->get(), 'totalRows' => $schedules->count()]);
    }

    public function list(Request $request)
    {

        $filters = json_decode($request->filters, TRUE) ?? false;
        $role = auth()->user()->role_id;
        $is_consultant = \App\Models\Role::where('id', $role)->first()->is_consultant;
        $is_incharge = \App\Models\Role::where('id', $role)->first()->is_consultant;
        $today = (request('today') && request('today') == '1') ?? false;
        $schedules = WorkflowSchedule::join('users', 'users.id', '=', 'workflow_schedules.user_id')
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->join('users as client', 'client.id', '=', 'workflow_schedules.client_id')
            ->leftJoin('instructions', 'instructions.id', '=', 'workflow_schedules.instruction')
            ->leftJoin('roles as rolecolor', 'rolecolor.id', '=', 'workflow_schedules.role')
            ->leftJoin('user_details', 'user_details.user_id', '=', 'users.id')
            ->leftJoin('feedbacks', 'feedbacks.workflow_session_id', '=', 'workflow_schedules.id')
            ->when($filters && !empty($filters), function ($q) use ($filters) {
                if (isset($filters['user_id'])) {
                    $q->where('workflow_schedules.user_id', $filters['user_id']);
                }
                if (isset($filters['client_id'])) {
                    $q->where('workflow_schedules.client_id', $filters['client_id']);
                }
            })->when($role == 10 || $role == 11, function ($q) {
                // $downgradedConsultant = User::join('user_details', 'user_details.user_id', '=', 'users.id')->join('roles', 'roles.id', '=', 'users.role_id')->where('roles.is_consultant', 1)->where('user_details.is_upgraded', 0)->pluck('users.id');
                // $q->whereNotIn('workflow_schedules.user_id', $downgradedConsultant);
            })->when($is_consultant == 1, function ($q) {
                $q->where('workflow_schedules.user_id', auth()->user()->id);
            })
            ->when($today, function ($q) {
                $q->whereDate('workflow_schedules.schedule_datettime', '=', date('Y-m-d'));
            })
            ->select('workflow_schedules.*', 'users.name as assignedto_user', 'client.name as client_name', 'rolecolor.color as color', 'user_details.incharge as incharge', 'feedbacks.rating as rating', 'instructions.id as instruction')->orderBy('workflow_schedules.schedule_datettime', 'DESC');
        $schedules = $schedules->limit($request->per_page)->offset(($request->page - 1) * $request->per_page)->get();
        foreach ($schedules as $key => $schedule) {
            if ($schedule->groupid) {
                $clients = WorkflowSchedule::where('groupid', $schedule->groupid)->where('client_id', '<>', $schedule->client_id)->pluck('client_id');
                $schedules[$key]->clients = $clients;
            } else {
                $schedules[$key]->clients = [];
            }
        }
        return response()->json([
            'success' => true,
            'schedules' => $schedules,
            'page' => $request->page,
            'total' => $schedules->count(),
            'per_page' => $request->per_page
        ]);
    }
    public function events(Request $request)
    {
        $filters = $request->filters ?? false;
        $filters = json_decode($request->filters, TRUE) ?? false;
        $userDetail = \App\Models\UserDetail::where('user_id', auth()->user()->id)->first();
        $role_users = null;
        if ($userDetail->incharge == 1) {
            $role_users = User::where('role_id', auth()->user()->role_id)->pluck('id')->toArray();
        }
        $schedules = WorkflowSchedule::join('users', 'users.id', '=', 'workflow_schedules.user_id')
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->join('users as client', 'client.id', '=', 'workflow_schedules.client_id')
            ->leftJoin('roles as rolecolor', 'rolecolor.id', '=', 'workflow_schedules.role')
            ->when($filters && !empty($filters), function ($q) use ($filters, $role_users) {
                if (isset($filters['user_id'])) {
                    if (!empty($role_users)) {
                        $q->whereIn('workflow_schedules.user_id', $role_users + array($filters['user_id']));
                    } else {
                        $q->where('workflow_schedules.user_id', $filters['user_id']);
                    }
                }
                if (isset($filters['client_id'])) {
                    $q->where('workflow_schedules.client_id', $filters['client_id']);
                }
            })->select('workflow_schedules.*', 'users.name as assignedto', 'client.name as client_name', DB::raw('DATE(workflow_schedules.schedule_datettime) as date'), 'rolecolor.color as color')->orderBy('id', 'DESC')->get();
        foreach ($schedules as $key => $schedule) {
            if ($schedule->status == 'Completed') {
                $schedules[$key]->color = '#008000';
            }
        }
        return response()->json([
            'success' => true,
            'events' => $schedules,
        ]);
    }
    public function updatestatus(Request $request)
    {
        $workschedule = WorkflowSchedule::find($request->id);
        $workschedule->status = $request->status;
        $workschedule->save();
        return response()->json([
            'success' => true,
            'message' => 'Schedule status updated successfully!',
        ]);
    }

    public function message(Request $request)
    {
        $messageinfo = $request->all();
        $message = new WorkflowMessage($messageinfo);
        $message->save();
        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully!',
        ]);
    }

    public function getMessageList($id)
    {
        $mesages = WorkflowMessage::where('schedule_id', $id)->get();
        return response()->json([
            'success' => true,
            'mesages' => $mesages,
        ]);
    }

    public function assign_consultant(Request $request)
    {
        $consultant = User::find($request->consultant_id);
        $workschedule = WorkflowSchedule::find($request->id);
        $workschedule->user_id = $request->consultant_id;
        $workschedule->role = $consultant->role_id;
        $workschedule->save();

        $data = [
            'client_id' => $workschedule->client_id,
            'consultant_id' =>  $request->consultant_id,
        ];
        ClientConsultant::updateOrCreate($data, $data);

        return response()->json([
            'success' => true,
            'message' => 'Schedule assigned successfully!',
        ]);
    }

    public function reschedule(Request $request)
    {
        $workschedule = WorkflowSchedule::find($request->id);
        $workschedule->schedule_datettime = date('Y-m-d H:i:s', strtotime($request->schedule_time));
        $workschedule->save();
        return response()->json([
            'success' => true,
            'message' => 'Workflow Rescheduled successfully!',
        ]);
    }

    public function createLink(Request $request, $id)
    {
        $schedule = WorkflowSchedule::join('users as client', 'client.id', '=', 'workflow_schedules.client_id')->select('workflow_schedules.*', 'client.name as client_name')->where('workflow_schedules.id', $id)->first();
        $frequency = 30;
        $url = config('services.enablex.url') . '/' . config('services.enablex.version');
        $timeslot_from = new DateTime($schedule->schedule_datettime);
        $timeslot_from = $timeslot_from->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode(config('services.enablex.app_id') . ":" . config('services.enablex.app_key')),
        ])->post("$url/rooms", [
            "name" => $schedule->title,
            "owner_ref" => auth()->user()->name . '-' . auth()->user()->id,
            "settings" => [
                "description" => $schedule->title,
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
            $meeting_url = config('services.enablex.domain');
            $schedule->participant_link =  $meeting_url . '/' . $response['room']['room_id'] . '?name=' . $schedule->client_name;
            $schedule->host_link = $meeting_url . '/' . 'host/' . base64_encode($response['room']['room_id'] . "-" . config('services.enablex.app_id')) . '?name=' . auth()->user()->name;
            $schedule->save();

            if (!empty($schedule->groupid)) {
                $otherschedules = WorkflowSchedule::join('users as client', 'client.id', '=', 'workflow_schedules.client_id')->select('workflow_schedules.*', 'client.name as client_name')->where('groupid', $schedule->groupid)->where('workflow_schedules.id', '<>', $schedule->id)->get();
                foreach ($otherschedules as $other) {
                    $other->participant_link =  $meeting_url . '/' . $response['room']['room_id'] . '?name=' . $other->client_name;
                    $other->host_link = $meeting_url . '/' . 'host/' . base64_encode($response['room']['room_id'] . "-" . config('services.enablex.app_id')) . '?name=' . auth()->user()->name;
                    $other->save();
                }
            }
            return response()->json(['success' => true, 'message' => 'video link generated successfully']);
        }
        return response()->json(['success' => false, 'message' =>  $response['error'][0] ?? 'Error generating video link']);
    }


    public function callNow(Request $request)
    {

        $no1 = auth()->user()->phone;
        if ($request->user_id != auth()->user()->id) {
            $user = User::find($request->user_id);
        } else {
            $user = User::find($request->client_id);
        }
        if ($user) {
            $no2 = $user->phone;
            (new Vi)->call($no1, $no2);
            return response()->json(['success' => true, 'message' => 'call connected successfully ']);
        }
        throw new Exception('Something went wrong while initiating call');
    }
}
