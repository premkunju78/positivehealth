<?php

namespace App\Http\Controllers;

use App\Models\AlianClient;
use App\Models\Availability;
use App\Models\Enquiry;
use App\Models\ClientConsultant;
use App\Models\CoordinatorAlliancepartner;
use App\Models\User;
use App\Models\Package;
use App\Models\WorkflowMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AppointConfToConsult;
use App\Notifications\AppointConfirmationToClient;
use App\utils\Helpers;
use DB;
use App\Services\Vi;

class EnquiryController extends Controller
{
    public function list(Request $request)
    {
        $filters = json_decode($request->filters, TRUE) ?? false;
        $role = auth()->user()->role_id;
        $role_name = \App\Models\Role::find($role)->name;
        $is_consultant = \App\Models\Role::find($role)->is_consultant;
        $query_type = $request->input('query_type');
        
        $enquiries = Enquiry::join('users as client', 'client.id', '=', 'enquiries.user_id')->join('roles as role', 'role.id', '=', 'client.role_id')
            ->when($role, function ($q) use ($role_name, $is_consultant, $query_type) {
                if ($role_name == 'client' || $role_name == 'alliance-partner' || $is_consultant == 1) {
                    $q->where(function($query){
                        $query
                        ->where('enquiries.user_id', auth()->user()->id)
                        ->orWhere('enquiries.assign_to', auth()->user()->id);
                    });
                } else if ($role_name == 'coordinator') {
                    $alliance_partner = CoordinatorAlliancepartner::where('coordinator_id', auth()->user()->id)->pluck('alliancepartner_id')->toArray();
                    $aliance_clients = AlianClient::whereIn('aliance_id', $alliance_partner)->pluck('client_id')->toArray();
                    $ids = array_merge($alliance_partner, $aliance_clients);
                    if (empty($ids)) {
                        $ids = array(0);
                    }
    
                    if($role_name != 'admin') {
                        $q->where('enquiries.assign_to', auth()->user()->id);
                    }

                } else if ($role_name == 'healthcoach') {
                    $q->where('enquiries.hc_id', auth()->user()->id);
                }

                if($query_type == 'enquiries') {
                    $q->where('enquiries.type', 'enquery');
                }elseif($query_type == 'queries'){
                    $q->where('enquiries.type', 'query');
                }

                
            })
            ->leftJoin('users as hc', 'enquiries.hc_id', '=', 'hc.id')
            ->leftJoin('users as ru', 'enquiries.replied', '=', 'ru.id')
            ->select('enquiries.*', 
            'client.name as client_name', 
            DB::raw("CONCAT(ru.first_name, ' ', ru.last_name) AS replied_by"),
            DB::raw("CASE WHEN '$query_type' = 'queries' THEN CONCAT('QU',SUBSTRING(enquiries.enquiry_id, 3)) ELSE enquiries.enquiry_id END AS enquiry_id"),
            DB::raw("CONCAT(LEFT(REGEXP_REPLACE(enquiry_detail, '(<[^>]*>)|(&nbsp;)', ''), 20), '...') as enquiry_detail"),
            'enquiries.enquiry_detail as enquiry_details', 
            'hc.name as hc_name','role.name as role_name', 
            DB::raw("CONCAT(UCASE(LEFT(role.name, 1)), SUBSTRING(role.name, 2)) as client_role"),
            DB::raw("DATE_FORMAT(enquiries.created_at, '%d %b %Y %H:%i %p') as created_at_view"),
            DB::raw("DATE_FORMAT(enquiries.updated_at, '%d %b %Y %H:%i %p') as replied_on")
            )
            ->orderBy('id', 'DESC');
        $s1 = clone $enquiries;
        return response()->json([
            'success' => true,
            'role' => $role,
            'enquiries' => $s1->limit($request->per_page)->offset(($request->page - 1) * $request->per_page)->get(),
            'page' => $request->page,
            'total' => $enquiries->count(),
            'per_page' => $request->per_page
        ]);
    }

    public function store(Request $request)
    {
        $request->validate($this->rules());
        $enquiryInfo = $request->all();
        $enquiryInfo['user_id'] = auth()->user()->id;
        $enquiry = new Enquiry($enquiryInfo);
        $enquiry->save();
        $enquiry_id = $enquiry->id;
        Enquiry::where('id', $enquiry_id)->update(['enquiry_id' => 'EN' . sprintf('%03d', $enquiry_id)]);
        return response()->json(['success' => true, 'message' => 'Enquiry created successfully']);
    }

    protected function rules()
    {
        return [
            'enquiry_detail' => 'required',
        ];
    }

    public function update(Request $request) {
        $params = $request->input('params');
        $enquiry_id = (int) $params['enquiry_id'];        
        $enquiry_comment = $params['enquiry_comment'];
        $enquiry = Enquiry::find($enquiry_id);
        $enquiry->comment = $enquiry_comment;
        $enquiry->status = 'Closed';
        $enquiry->replied = auth()->user()->id;
        $enquiry->save();

        return response()->json([
            'success' => true,
            'message' => 'Reply Sent Successfully!',
        ]);
    }

    public function updatestatus(Request $request)
    {
        $enquiry = Enquiry::find($request->id);
        $enquiry->status = ($enquiry->status == 'Open') ? 'Closed' : 'Open';
        $enquiry->save();
        return response()->json([
            'success' => true,
            'message' => 'Enquiry status updated successfully!',
        ]);
    }

    public function hcassignment(Request $request)
    {
        $enquiry = Enquiry::find($request->enquiry_id);
        $enquiry->assign_to = $request->user;
        $enquiry->status = 'Assigned';
        $enquiry->save();
        $user = User::find($request->user);
        
        return response()->json([
            'success' => true,
            'message' => 'Enquiry assigned to ' . $user->first_name . ' successfully!',
        ]);
    }

    public function create_for_program (Request $request) {
        $program_id = $request->input('program_id');
        $user_id = auth()->user()->id;
        $program = Package::find($program_id);

        $enquiry = new Enquiry();
        $enquiry->user_id = $user_id;
        $enquiry->enquiry_detail = $program->title;
        $enquiry->type = 'enquery';
        $enquiry->save();

        $enquiry_id = $enquiry->id;

        $enquiry->update(['enquiry_id' => 'EN' . sprintf('%03d', $enquiry_id)]);

        return response()->json(['success' => true, 'message' => 'Enquiry created successfully']);
    }

    public function call(Request $request)
    {
        $no1 = '9440512838'; //auth()->user()->phone;
        $user_to_id = $request->user_id;
        $user_to = User::find($user_to_id);
        $no2 = '9120892125';//$user_to->phone;

        try {
            $return  = (new Vi)->call($no1, $no2);
            return response()->json(['success' => true, 'message' => 'call connected successfully ']);
        } catch (Exception $e) {
            throw new Exception('Something went wrong while initiating call');            
        }
    }

}
