<?php

namespace App\Http\Controllers;

use App\Models\AlianClient;
use App\Models\Booking;
use App\Models\ClientConsultant;
use App\Models\MarketingexecutiveAlliancepartner;
use App\Models\MarketingheadAlliancepartner;
use App\Models\DiagnosticheadClient;
use App\Models\Lead;
use App\Models\User;

use App\Models\Prescription;
use App\Models\WorkflowSchedule;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function consultantCounts()
    {
        $counts = [
            [
                'title'  => 'Clients',
                'subtitle' => ClientConsultant::where('consultant_id', auth()->user()->id)->count(),
                'color'  => 'success',
                'icon' => 'PlusCircleIcon',
                'customClass'  => ''
            ],
            [
                'title'  => 'Appointments',
                'subtitle' => WorkflowSchedule::where('user_id', auth()->user()->id)->count(),
                'color'  => 'secondary',
                'icon' => 'CalendarIcon',
                'customClass'  => ''
            ],
            [
                'title'  => 'Revenue',
                'subtitle' => 0.00,
                'color'  => 'secondary',
                'icon' => 'CalendarIcon',
                'customClass'  => ''
            ],
            [
                'title'  => 'Leads',
                'subtitle' => Lead::where('user_id', auth()->user()->id)->count(),
                'color'  => 'secondary',
                'icon' => 'CalendarIcon',
                'customClass'  => ''
            ],
        ];
        if (auth()->user()->role_id == '15') {
            $alliancepartners=MarketingheadAlliancepartner::where('mh_id',auth()->user()->id)->pluck('alliancepartner_id');
            $clients=AlianClient::whereIn('aliance_id',$alliancepartners)->pluck('client_id');
            $counts = [
                [
                    'title'  => 'No of AP',
                    'subtitle' => count($alliancepartners),
                    'color'  => 'success',
                    'icon' => 'UsersIcon',
                    'customClass'  => ''
                ],
                [
                    'title'  => 'No of Clients',
                    'subtitle' =>  count($clients),
                    'color'  => 'secondary',
                    'icon' => 'UsersIcon',
                    'customClass'  => ''
                ],
                [
                    'title'  => 'Revenue',
                    'subtitle' => 0.00,
                    'color'  => 'secondary',
                    'icon' => 'CalendarIcon',
                    'customClass'  => ''
                ],
            ];
        }
        if (auth()->user()->role_id == '16') {
            $alliancepartners=MarketingexecutiveAlliancepartner::where('me_id',auth()->user()->id)->pluck('alliancepartner_id');
            $clients=AlianClient::whereIn('aliance_id',$alliancepartners)->pluck('client_id');
            $counts = [
                [
                    'title'  => 'No of AP',
                    'subtitle' => count($alliancepartners),
                    'color'  => 'success',
                    'icon' => 'UsersIcon',
                    'customClass'  => ''
                ],
                [
                    'title'  => 'No of Clients',
                    'subtitle' =>  count($clients),
                    'color'  => 'secondary',
                    'icon' => 'UsersIcon',
                    'customClass'  => ''
                ],
                [
                    'title'  => 'Revenue',
                    'subtitle' => 0.00,
                    'color'  => 'secondary',
                    'icon' => 'CalendarIcon',
                    'customClass'  => ''
                ],
            ];
        }
        if (auth()->user()->role_id == '17') {
            $clients=DiagnosticheadClient::where('dh_id',auth()->user()->id)->pluck('client_id');
            $dataentry_operators=User::where('role_id',19)->where('created_by',auth()->user()->id)->pluck('id');
            $pickup_persons=User::where('role_id',19)->where('created_by',auth()->user()->id)->pluck('id');
            $counts = [
                [
                    'title'  => 'No of clients',
                    'subtitle' => count($clients),
                    'color'  => 'success',
                    'icon' => 'UsersIcon',
                    'customClass'  => ''
                ],
                [
                    'title'  => 'Data entry operator',
                    'subtitle' =>  count($dataentry_operators),
                    'color'  => 'secondary',
                    'icon' => 'UsersIcon',
                    'customClass'  => ''
                ],
                [
                    'title'  => 'Pickup person',
                    'subtitle' =>  count($pickup_persons),
                    'color'  => 'secondary',
                    'icon' => 'CalendarIcon',
                    'customClass'  => ''
                ],
            ];
        }
        return response()->json([
            'success' => true,
            'counts' => $counts
        ]);
    }
    public function upcomingAppointment()
    {
        $appointment = Booking::whereDate('timeslot_from', '=', date('Y-m-d'))
            ->whereTime('timeslot_to', '>=', date('H:i:s'))
            ->join('users', 'users.id', '=', 'bookings.user_id')
            ->select('bookings.*', 'users.name as consultant')
            ->where('booked_by', auth()->user()->id)
            ->first();

        return response()->json([
            'success' => true,
            'appointment' => $appointment
        ]);
    }
}
