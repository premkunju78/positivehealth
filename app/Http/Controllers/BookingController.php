<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use App\Models\Booking;
use App\Models\ClientConsultant;
use App\Models\WorkflowSchedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AppointConfToConsult;
use App\Notifications\AppointConfirmationToClient;
use App\utils\Helpers;
use Illuminate\Console\Scheduling\Schedule;

class BookingController extends Controller
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

        $appointInfo = $request->all();

        $appointInfo['transaction_date'] = date('Y-m-d');

        $bookings = new Booking($appointInfo);

        $bookings->save();

        $clientDetail = User::find($appointInfo['booked_by']);

        $consultantDetail = User::find($appointInfo['user_id']);

        $ClientConsultant = ClientConsultant::firstOrNew(['client_id' => $appointInfo['booked_by'], 'consultant_id' => $appointInfo['user_id']]);
        $ClientConsultant->save();

        // Mail to Client
        Notification::route('mail', $clientDetail->email)->notify(new AppointConfirmationToClient($appointInfo, $consultantDetail));

        // Mail to Consultant
        Notification::route('mail', $consultantDetail->email)->notify(new AppointConfToConsult($appointInfo, $consultantDetail));

        return response()->json(['success' => true, 'message' => 'slot booked successfully']);
    }

    protected function rules()
    {
        return [
            'name' => 'required',
            'timeslot_from' => 'required',
            'timeslot_to' => 'required',
            'description' => 'sometimes',
            'user_id' => 'required',
            'booked_by' => 'required'
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
                $q->whereDate('timeslot_from', '<', date('Y-m-d'));
            }, function ($q) {
                $q->whereDate('timeslot_from', '>=', date('Y-m-d'));
            });
        return response()->json(['success' => true, 'schedules' => $schedules->get(), 'totalRows' => $schedules->count()]);
    }

    public function list(Request $request)
    {
        $filters = $request->filters ?? false;
        $role = auth()->user()->role_id;
        $schedules = Booking::join('users', 'users.id', '=', 'bookings.user_id')
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->join('user_details', 'user_details.user_id', '=', 'users.id')
            ->when($filters && !empty($filters), function ($q) use ($filters) {
                if (isset($filters['role'])) {
                    $q->where('roles.id', $filters['role']);
                }
                if (isset($filters['from']) & isset($filters['to'])) {
                    $q->whereBetween('bookings.timeslot_from', [$filters['from'], $filters['to'] . " 23:59:00"]);
                }
            })->when($role == 10, function ($q) {
                $q->where('user_details.league', '1');
            })->select('users.name as consultant', 'roles.name as role', 'bookings.*');

        $s1 = clone $schedules;
        return response()->json([
            'success' => true,
            'schedules' => $s1->limit($request->per_page)->offset(($request->page - 1) * $request->per_page)->get(),
            'page' => $request->page,
            'total' => $schedules->count(),
            'per_page' => $request->per_page
        ]);
    }

    public function todayMeetings()
    {
        $type = request('type') ?? 'today';
        $schedules = Booking::when($type == 'today', function ($q) {
            $q->whereDate('timeslot_from', '=', date('Y-m-d'))
                ->whereTime('timeslot_to', '>=', date('H:i:s'));
        })
            ->where(function ($q) {
                if (auth()->user()->id != '1') {
                    $q->where('booked_by', auth()->user()->id)
                        ->orWhere('user_id', auth()->user()->id);
                }
            })
            ->join('users', 'users.id', '=', 'bookings.user_id')
            ->select('bookings.*', 'users.name as creator');

        return response()->json(['success' => true, 'schedules' => $schedules->get(), 'totalRows' => $schedules->count()]);
    }

    public function updatestatus(Request $request)
    {
        $schedule = Booking::find($request->id);
        if (!empty($schedule)) {
            $schedule->status = $request->status;
            $schedule->save();
            return response()->json(['success' => true, 'message' => 'Booking status updated successfully!']);
        } else {
            return response()->json(['success' => false, 'message' => 'Booking not found!']);
        }
    }

    public function trigger_enablex_webhook_request(Request $request) {
        $request_data = $request->all();
        if($request_data['type'] == "session_start") {
            $room_id = $request_data['room_id'];
            WorkflowSchedule::where('participant_link', 'LIKE', '%'.$room_id.'%')->update([
                'call_start_on' => date('Y-m-d H:i:s'),
            ]);
        }

        if($request_data['type'] == "session_stop") {
            $room_id = $request_data['room_id'];
            WorkflowSchedule::where('participant_link', 'LIKE', '%'.$room_id.'%')->update([
                'call_end_on' => date('Y-m-d H:i:s'),
                'is_call_end' => 1
            ]);
        }

        $content = json_encode($request_data);
        $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/".$request_data['type']."-myEnablex.txt","wb");
        fwrite($fp,$content);
        fclose($fp);
    }
}
