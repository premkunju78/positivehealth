<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use App\Models\WorkflowSchedule;
use App\Models\ClientConsultant;
use App\Models\User;
use App\Models\WorkflowMessage;
use App\Models\ClientGroup;
use App\Models\ClientGroupMapping;
use App\Models\ClientGroupUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AppointConfToConsult;
use App\Notifications\AppointConfirmationToClient;
use App\utils\Helpers;
use App\Services\Vi;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Redis;
use App\Models\Booking;
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
        if ($request->reschedule_id) {
            $schedule = WorkflowSchedule::find($request->reschedule_id);
            $schedule->user_id = $request->input('user_id');            
            $schedule->assignedto = $request->input('assignedto');            
            $schedule->status = $request->input('status');            
            $schedule->schedule_datettime = date('Y-m-d H:i:s', strtotime($request->schedule_time));
            $schedule->save();            
            return response()->json(['success' => true, 'message' => 'Reschedule successfully']);
            exit();
        }else{
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

            $aliance_id = WorkflowSchedule::leftJoin('alian_client', 'alian_client.client_id', '=', 'workflow_schedules.client_id')
            ->where('workflow_schedules.id', $schedule->id)
            ->value('alian_client.aliance_id');

            $scheduled_no = 'AP-'.$aliance_id.'/CL-'.$schedule->client_id.'/P-'.$schedule->id;
            $schedule->session = $request->session;
            $schedule->scheduled_no = $scheduled_no;
            $schedule->save();
        }

        return response()->json(['success' => true, 'message' => 'Schedule saved successfully']);
    }

    protected function rules()
    {
        return [
            'client_id' => 'required',
            'title' => 'required',
            'schedule_time' => 'required',
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
        $type = $request->input('type');
        $appointment_type = $request->input('appointment_type');

        $filters = json_decode($request->filters, TRUE) ?? false;
        $role = auth()->user()->role_id;
        $is_consultant = \App\Models\Role::where('id', $role)->first()->is_consultant;
        $is_incharge = \App\Models\Role::where('id', $role)->first()->is_consultant;

        if ($appointment_type == 'for_programs' || !$appointment_type) {

            $schedules = WorkflowSchedule::join('users', 'users.id', '=', 'workflow_schedules.user_id')
                ->join('roles', 'roles.id', '=', 'users.role_id')
                ->join('users as client', 'client.id', '=', 'workflow_schedules.client_id')
                ->join('alian_client', 'alian_client.client_id', '=', 'workflow_schedules.client_id')
                ->join('user_details as aliance_p', 'aliance_p.user_id', '=', 'alian_client.aliance_id')
                ->join('user_details as client_details', 'client_details.user_id', '=', 'workflow_schedules.client_id')
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
                    if (isset($filters['created_by'])) {
                        $q->where('workflow_schedules.created_by', $filters['created_by']);
                    }
                })->when($role == 10 || $role == 11, function ($q) {
                    // $downgradedConsultant = User::join('user_details', 'user_details.user_id', '=', 'users.id')->join('roles', 'roles.id', '=', 'users.role_id')->where('roles.is_consultant', 1)->where('user_details.is_upgraded', 0)->pluck('users.id');
                    // $q->whereNotIn('workflow_schedules.user_id', $downgradedConsultant);
                })->when($is_consultant == 1, function ($q) {
                    $q->where('workflow_schedules.user_id', auth()->user()->id);
                })
                ->when($type == 'todays', function ($q) {
                    $q->whereDate('workflow_schedules.schedule_datettime', '=', date('Y-m-d'));
                })
                ->when($type == 'past', function ($q) {
                    $q->whereDate('workflow_schedules.schedule_datettime', '<', date('Y-m-d'));
                })
                ->when($type == 'upcoming', function ($q) {
                    $q->whereDate('workflow_schedules.schedule_datettime', '>', date('Y-m-d'));
                })
                ->select('workflow_schedules.*', 'users.name as assignedto_user', 'client.name as client_name', 'client_details.gender as client_gender', 'client_details.dob', DB::raw("TIMESTAMPDIFF(YEAR, client_details.dob, CURDATE()) AS client_age"), 'rolecolor.color as color', 'user_details.incharge as incharge', 'feedbacks.rating as rating', 'instructions.id as instruction', DB::raw('DATE_FORMAT(workflow_schedules.schedule_datettime, "%d %b %Y") as scheduled_date'), DB::raw('TIME_FORMAT(workflow_schedules.schedule_datettime, "%i:%H %p") as scheduled_time'), DB::raw("CONCAT(aliance_p.company_name) as ap_name"))->orderBy('workflow_schedules.id', 'DESC');
            $schedules = $schedules->limit($request->per_page)->offset(($request->page - 1) * $request->per_page)->get();
            foreach ($schedules as $key => $schedule) {
                if ($schedule->groupid) {
                    $clients = WorkflowSchedule::where('groupid', $schedule->groupid)->where('client_id', '<>', $schedule->client_id)->pluck('client_id');
                    $schedules[$key]->clients = $clients;
                } else {
                    $schedules[$key]->clients = [];
                }
            }

            $scheduleArr = $schedules ? $schedules->toArray() : [];        

        } elseif ($appointment_type == 'for_consultations') {
            $schedules = Booking::select(DB::raw('DATE_FORMAT(bookings.timeslot_from, "%d %b %Y") as scheduled_date'), DB::raw('TIME_FORMAT(bookings.timeslot_from, "%i:%H %p") as scheduled_time'), 'client.name as client_name', 'client_details.gender as client_gender', 'client_details.dob', DB::raw("TIMESTAMPDIFF(YEAR, client_details.dob, CURDATE()) AS client_age"), 'aliance_p.company_name as ap_name', 'bookings.status', DB::raw("CONCAT('AP-', aliance_p.user_id, '/CL-', bookings.booked_by, '/C-', bookings.id) as scheduled_no"), 'bookings.type', 'bookings.razorpay_payment_id','bookings.cost', 'bookings.description')
            ->join('users as client', 'client.id', '=', 'bookings.booked_by')
            ->join('alian_client', 'alian_client.client_id', '=', 'bookings.booked_by')
            ->join('user_details as aliance_p', 'aliance_p.user_id', '=', 'alian_client.aliance_id')
            ->join('user_details as client_details', 'client_details.user_id', '=', 'bookings.booked_by')
            ->when($is_consultant == 1, function ($q) {
                $q->where('bookings.user_id', auth()->user()->id);
            })
            ->when($type == 'todays', function ($q) {
                $q->whereDate('bookings.timeslot_from', '=', date('Y-m-d'));
            })
            ->when($type == 'past', function ($q) {
                $q->whereDate('bookings.timeslot_from', '<', date('Y-m-d'));
            })
            ->when($type == 'upcoming', function ($q) {
                $q->whereDate('bookings.timeslot_from', '>', date('Y-m-d'));
            });

            $schedules = $schedules
            ->orderBy('bookings.id', 'DESC')
            ->limit($request->per_page)
            ->offset(($request->page - 1) * $request->per_page)
            ->get();

            $scheduleArr = $schedules ? $schedules->toArray() : [];
        
        } elseif ($appointment_type == 'for_group_consultations') {

            $schedules = ClientGroupMapping::select('client_groups.*','client.id as client_id','client.name as client_name','client_details.gender as client_gender', 'client_details.dob', DB::raw("TIMESTAMPDIFF(YEAR, client_details.dob, CURDATE()) AS client_age"), 'aliance_p.company_name as ap_name')
            ->join('client_groups', 'client_groups.id', '=', 'client_group_mappings.group_id')
            ->join('users as client', 'client.id', '=', 'client_group_mappings.client_id')
            ->join('alian_client', 'alian_client.client_id', '=', 'client_group_mappings.client_id')
            ->join('user_details as aliance_p', 'aliance_p.user_id', '=', 'alian_client.aliance_id')
            ->join('user_details as client_details', 'client_details.user_id', '=', 'client_group_mappings.client_id');

            if ($role === 3) {                
                $schedules->where('client_group_mappings.client_id', (int) auth()->user()->id);
            }

            $schedules = $schedules
            // ->orderBy('bookings.id', 'DESC')
            ->limit($request->per_page)
            ->offset(($request->page - 1) * $request->per_page)
            ->get();

            $scheduleArr = [];

            $groupScheduleArr = $schedules ? $schedules->toArray() : [];
            if (!empty($groupScheduleArr)) {
                foreach ($groupScheduleArr as $group_schedule) {
                    $group_sessions = json_decode($group_schedule['sessions'], true);
                    if (isset($group_sessions['start_date'])) {
                        foreach ($group_sessions['start_date'] as $session_key => $start_date) {
                            $session_number = $session_key+1;
                            $meeting_url = config('services.enablex.domain');
                            $participant_link = $meeting_url . '/' . $group_sessions['room_id'][$session_key] . '?name=' . $group_schedule['client_name'].'&client_id='.$group_schedule['client_id'];
                            $scheduled_no = 'CLGP/'.$group_schedule['id'].'/S/'.$session_number;
                            $_sessions_arr['scheduled_date'] = $start_date;
                            $_sessions_arr['scheduled_time'] = date('h:i A', strtotime($group_sessions['start_time'][$session_key]));       
                            $_sessions_arr['scheduled_end_time'] = date('h:i A', strtotime($group_sessions['end_time'][$session_key]));       
                            $_sessions_arr['duration'] = $group_sessions['duration'][$session_key];       
                            $_sessions_arr['title'] = $group_sessions['title'][$session_key];       
                            $_sessions_arr['assigned_consultant'] = $group_sessions['assigned_consultant'][$session_key];
                            $_sessions_arr['session_title'] = $group_sessions['title'][$session_key]; 
                            $_sessions_arr['meeting_link'] = $is_consultant ? $group_sessions['meeting_link'][$session_key] : $participant_link; 
                            $_sessions_arr['type'] = 'video'; 
                            // $_sessions_arr['group_details'] = '<strong>'.$group_schedule['group_name'].' ('.$group_schedule['group_type'].')</strong>'.'<br/><br/>'.'No. of participants: <strong>'.$group_schedule['no_of_participants'].'</strong><br/>Pay Type: <strong>'.ucfirst($group_schedule['session_type']).'</strong><br/>Total Sessions: <strong>'.$group_schedule['session_no'].'</strong><br/>Cost: <strong>'.$group_schedule['payment_amount'].'</strong>';
                            $_sessions_arr['group_details'] = '<strong>'.$group_schedule['group_name'].' ('.$group_schedule['group_type'].')</strong>';
                            $_sessions_arr['session_type'] = $group_schedule['session_type']; 
                            $_sessions_arr['no_of_participants'] = $group_schedule['no_of_participants']; 
                            $_sessions_arr['description'] = $group_schedule['description']; 
                            $_sessions_arr['client_name'] = $group_schedule['client_name']; 
                            $_sessions_arr['client_gender'] = $group_schedule['client_gender']; 
                            $_sessions_arr['client_age'] = $group_schedule['client_age']; 
                            $_sessions_arr['ap_name'] = $group_schedule['ap_name']; 
                            $_sessions_arr['cost'] = $group_schedule['payment_amount']; 
                            $_sessions_arr['status'] = $group_schedule['status']; 
                            $_sessions_arr['detail_link'] = URL("/clients/payment/".base64_encode(time())."/".base64_encode($group_schedule['id']));
                            $_sessions_arr['scheduled_no'] = $scheduled_no; 

                            if (
                                (($type == 'todays' && date('Y-m-d', strtotime($start_date)) == date('Y-m-d'))
                                || ($type == 'upcoming' && date('Y-m-d', strtotime($start_date)) > date('Y-m-d'))
                                || ($type == 'past' && date('Y-m-d', strtotime($start_date)) < date('Y-m-d')))
                                && !in_array($scheduled_no, array_column($scheduleArr, 'scheduled_no'))
                            ) {
                                if ($is_consultant) {
                                    if ($_sessions_arr['assigned_consultant'] ===  (int) auth()->user()->id) {
                                        $scheduleArr[] = $_sessions_arr;                                    
                                    }
                                } else {
                                    $scheduleArr[] = $_sessions_arr;                                    
                                }

                            }      
                        }
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'schedules' => $scheduleArr,
            'page' => $request->page,
            'total' => $schedules->count(),
            'per_page' => $request->per_page
        ]);
    }
    public function events(Request $request)
    {
        $filters = $request->filters ?? false;
        $role = auth()->user()->role_id;
        $is_consultant = \App\Models\Role::where('id', $role)->first()->is_consultant;
        $is_incharge = \App\Models\Role::where('id', $role)->first()->is_consultant;
        $filters = json_decode($request->filters, TRUE) ?? false;
        $userDetail = \App\Models\UserDetail::where('user_id', auth()->user()->id)->first();
        $role_users = null;
        $type = $request->input('type') ?? 'todays';

        if ($userDetail && $userDetail->incharge == 1) {
            $role_users = User::where('role_id', auth()->user()->role_id)->pluck('id')->toArray();
        }

        if ($request->consultation_type === 'regular' || !$request->consultation_type) {

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
        } elseif ($request->consultation_type === 'workshop') {

            $schedules = ClientGroupMapping::select('client_groups.*','client.id as client_id','client.name as client_name','client_details.gender as client_gender', 'client_details.dob', DB::raw("TIMESTAMPDIFF(YEAR, client_details.dob, CURDATE()) AS client_age"), 'aliance_p.company_name as ap_name')
            ->join('client_groups', 'client_groups.id', '=', 'client_group_mappings.group_id')
            ->join('users as client', 'client.id', '=', 'client_group_mappings.client_id')
            ->join('alian_client', 'alian_client.client_id', '=', 'client_group_mappings.client_id')
            ->join('user_details as aliance_p', 'aliance_p.user_id', '=', 'alian_client.aliance_id')
            ->join('user_details as client_details', 'client_details.user_id', '=', 'client_group_mappings.client_id');

            if ($role === 3) {                
                $schedules->where('client_group_mappings.client_id', (int) auth()->user()->id);
            }

            $schedules = $schedules
            ->get();

            $scheduleArr = [];
            $groupScheduleArr = $schedules ? $schedules->toArray() : [];

            if (!empty($groupScheduleArr)) {
                foreach ($groupScheduleArr as $group_schedule) {
                    $group_color = '#' . Helpers::random_color();
                    $group_sessions = json_decode($group_schedule['sessions'], true);
                    if (isset($group_sessions['start_date'])) {
                        foreach ($group_sessions['start_date'] as $session_key => $start_date) {
                            $session_number = $session_key+1;
                            $meeting_url = config('services.enablex.domain');
                            $participant_link = $meeting_url . '/' . $group_sessions['room_id'][$session_key] . '?name=' . $group_schedule['client_name'].'&client_id='.$group_schedule['client_id'];
                            $scheduled_no = 'CLGP/'.$group_schedule['id'].'/S/'.$session_number;
                            $_sessions_arr['scheduled_date'] = $start_date;
                            $_sessions_arr['schedule_datettime'] = date('Y-m-d H:i:s', strtotime($start_date . ' ' . $group_sessions['start_time'][$session_key]));       
                            $_sessions_arr['scheduled_end_time'] = date('h:i A', strtotime($group_sessions['end_time'][$session_key]));
                            $_sessions_arr['call_start_on'] = $_sessions_arr['schedule_datettime'];       
                            $_sessions_arr['date'] = $_sessions_arr['schedule_datettime'];       
                            $_sessions_arr['created_at'] = $_sessions_arr['schedule_datettime'];       
                            $_sessions_arr['duration'] = $group_sessions['duration'][$session_key];       
                            $_sessions_arr['title'] = $group_sessions['title'][$session_key];       
                            $_sessions_arr['assignedto'] = $group_sessions['assigned_consultant'][$session_key] ? User::find((int) $group_sessions['assigned_consultant'][$session_key])->name : '';
                            $_sessions_arr['session_title'] = $group_sessions['title'][$session_key]; 
                            $_sessions_arr['meeting_link'] = $is_consultant ? $group_sessions['meeting_link'][$session_key] : $participant_link; 
                            $_sessions_arr['type'] = 'video'; 
                            $_sessions_arr['group_details'] = '<strong>'.$group_schedule['group_name'].' ('.$group_schedule['group_type'].')</strong>';
                            $_sessions_arr['session_type'] = $group_schedule['session_type']; 
                            $_sessions_arr['no_of_participants'] = $group_schedule['no_of_participants']; 
                            $_sessions_arr['description'] = $group_schedule['description']; 
                            $_sessions_arr['client_name'] = $group_schedule['client_name']; 
                            $_sessions_arr['client_gender'] = $group_schedule['client_gender']; 
                            $_sessions_arr['client_age'] = $group_schedule['client_age']; 
                            $_sessions_arr['ap_name'] = $group_schedule['ap_name']; 
                            $_sessions_arr['cost'] = $group_schedule['payment_amount']; 
                            $_sessions_arr['status'] = $group_schedule['status']; 
                            $_sessions_arr['detail_link'] = URL("/clients/payment/".base64_encode(time())."/".base64_encode($group_schedule['id']));
                            $_sessions_arr['scheduled_no'] = $scheduled_no; 
                            $_sessions_arr['backgroundColor'] = $group_color;
                            $_sessions_arr['borderColor'] = $group_color;
                            $_sessions_arr['textColor'] = $group_color;
                            $_sessions_arr['eventColor'] = $group_color;
                            $_sessions_arr['color'] = $group_color;
                            $_sessions_arr['id'] = $group_schedule['id'];

                            if (!in_array($scheduled_no, array_column($scheduleArr, 'scheduled_no'))) {
                                if ($is_consultant) {
                                    if (isset($_sessions_arr['assigned_consultant']) && $_sessions_arr['assigned_consultant'] ===  (int) auth()->user()->id) {
                                        $scheduleArr[] = $_sessions_arr;                                    
                                    }
                                } else {
                                    $scheduleArr[] = $_sessions_arr;                                    
                                }

                            }      
                        }
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'events' => isset($scheduleArr) && !empty($scheduleArr) ? $scheduleArr : $schedules,
        ]);
    }

    public function updatestatus(Request $request)
    {
        $workschedule = WorkflowSchedule::find($request->id);
        $workschedule->status = $request->status;
        if ($request->status == 'canceled') {
            $workschedule->cancellation_reason = $request->cancellation_reason;
        }
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
        $workschedule->status = 'Pending';
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
            $schedule->participant_link =  $meeting_url . '/' . $response['room']['room_id'] . '?name=' . $schedule->client_name.'&client_id='.$schedule->client_id;
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

    public function checkRunningScheduleStatus(Request $request, $schedule_id) {
        $scheduled_call_ended = WorkflowSchedule::where(['id' => $schedule_id, 'is_call_end' => 1])->first();
        if ($scheduled_call_ended) {
            return response()->json(['success' => true, 'trigger_call_ended_screen' => '1']);
        }
        return response()->json(['success' => false, 'message' =>  'Schedule no found']);
    }    

    public function callNow(Request $request)
    {

        $no1 = auth()->user()->phone;
        if ($request->user_id != auth()->user()->id || $request->id != auth()->user()->id) {
            $user_id = $request->user_id ?? $request->id; 
            $user = User::find($user_id);
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
