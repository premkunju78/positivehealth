<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\LeadProgram;
use App\Models\User;
use App\Models\HealthcoachLead;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use DB;
use Mail;
use DateTime;


class LeadController extends Controller
{
    public function index(Request $request)
    {
        $role = auth()->user()->role_id;
        $user = $request->user_id ?? null;
        $is_consultant = \App\Models\Role::where('id', $role)->first()->is_consultant;
        $type = $request->type ?? null;
        $leads =  Lead::select('leads.*', 'alliancepartner_detail.company_name as alliancepartner_name')
            ->join('users as alliancepartner', 'alliancepartner.id', '=', 'leads.alliancepartner_id')
            ->leftJoin('user_details as alliancepartner_detail', 'alliancepartner_detail.user_id', '=', 'leads.alliancepartner_id')
            ->when($type, function ($q) use ($type) {
                switch ($type) {
                    case 'General':
                        // $q->whereNotNull('user_id')
                        //     ->join('users', 'users.id', '=', 'leads.user_id')
                        //     ->addSelect('users.name as consultant');
                        break;
                    case 'Website':
                        $q->whereNull('user_id');
                        break;
                }
            })->when($role, function ($q) use ($role) {
                if ($role === 2) {
                    $q->where('leads.alliancepartner_id', auth()->user()->id);
                }
                if ($role === 10) {
                    $alliancepartners = DB::table('coordinator_alliancepartners')->where('coordinator_id', auth()->user()->id)->pluck('alliancepartner_id')->toArray();
                    $q->whereIn('leads.alliancepartner_id', $alliancepartners);
                }
                if ($role === 11) {
                    $lead_id = DB::table('healthcoach_leads')->where('healthcoach_id', auth()->user()->id)->pluck('lead_id')->toArray();
                    $q->whereIn('leads.id', $lead_id);
                }
                if ($role === 15) {
                    $alliancepartners = DB::table('marketinghead_alliancepartners')->where('mh_id', auth()->user()->id)->pluck('alliancepartner_id')->toArray();
                    $q->whereIn('leads.alliancepartner_id', $alliancepartners);
                }
                if ($role === 16) {
                    $alliancepartners = DB::table('marketingexecutive_alliancepartners')->where('me_id', auth()->user()->id)->pluck('alliancepartner_id')->toArray();
                    $q->whereIn('leads.alliancepartner_id', $alliancepartners);
                }
            })->when($is_consultant == 1, function ($q) {
                $alliancepartners = DB::table('alian_consultants')->where('consultant_id', auth()->user()->id)->pluck('aliance_id')->toArray();
                $q->whereIn('leads.alliancepartner_id', $alliancepartners)->where('leads.user_id', auth()->user()->id);
            });
        $l1 = clone $leads;
        $leads_datas = $l1->limit($request->per_page)->offset(($request->page - 1) * $request->per_page)->get();
        foreach ($leads_datas as $key => $leads_data) {
            $lead_program = DB::table('lead_programs')->where('lead_id', $leads_data->id)->first();
            $assigned_hc = DB::table('healthcoach_leads')->where('lead_id', $leads_data->id)->first();
            if (!empty($lead_program)) {
                $leads_datas[$key]->payment_link = URL('/') . '/lead/programpayment/' . $lead_program->id;
            } else {
                $leads_datas[$key]->payment_link = '';
            }
            if (!empty($assigned_hc)) {
                $healthcoach = \App\Models\User::find($assigned_hc->healthcoach_id);
                $leads_datas[$key]->healthcoach_name = $healthcoach->name;
                $leads_datas[$key]->hc_assigned = true;
            } else {
                $leads_datas[$key]->healthcoach_name = '';
                $leads_datas[$key]->hc_assigned = false;
            }
        }
        return response()->json([
            'leads' =>  $leads_datas,
            'page' => $request->page,
            'total' => $leads->count(),
            'per_page' => $request->per_page
        ]);
    }

    public function list(Request $request)
    {
        $user = $request->user_id ?? null;
        $leads =  Lead::select('name as label', 'id')->when($user, function ($q) use ($user) {
            $q->where('leads.user_id', $user);
        });
        $l1 = clone $leads;
        return response()->json([
            'leads' =>  $l1->get(),
        ]);
    }

    private function rules($id = null)
    {
        return [
            'name' => 'required',
            'email' => (!empty($id)) ? 'unique:leads,email,' . $id : 'required|unique:leads,email',
            'user_id' => 'sometimes',
            'phone' => 'required',
            'concern' => 'required'
        ];
    }


    public function register(Request $request)
    {
        // $request->validate($this->rules());
        // $data = $request->all();
        // $lead = Lead::create($data);

        // if ($request->wantsJson() || $request->ajax()) {
        //     return response()->json([
        //         'success' => true,
        //         'message' => 'Lead generated succesfullyy',
        //         'lead' => $lead
        //     ]);
        // } else {
        //     return back()->with('success', 'Lead generated succesfully');
        // }

        $request->validate($this->rules());
        $user = new \App\Models\User;
        $user->first_name = $request->name;
        $user->last_name = '';
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $user->role_id = 3;
        $user->phone = $request->phone;
        $user->save();

        $role_user = new \App\Models\RoleUser;
        $role_user->user_id = $user->id;
        $role_user->role_id = 3;
        $role_user->save();

        $alian_client = new \App\Models\AlianClient;
        $alian_client->client_id = $user->id;
        $alian_client->aliance_id = $request->alliancepartner_id;
        $alian_client->referred = '1';
        if (!empty($hc_lead)) {
            $alian_client->cis_id = $hc_lead->healthcoach_id;
        }
        $alian_client->save();
        $data = array();
        $data['user_id'] = $user->id;
        $data['gender'] = $request->gender;
        $data['dob'] = $request->dob;
        \App\Models\UserDetail::create($data);
        $notification_to = array();
        $admin = \App\Models\User::where('role_id', 1)->first();
        $coordinator = \App\Models\CoordinatorAlliancepartner::where('alliancepartner_id', $request->alliancepartner_id)->first();
        $marketinghead = \App\Models\MarketingheadAlliancepartner::where('alliancepartner_id', $request->alliancepartner_id)->first();
        $marketingexecutive = \App\Models\MarketingexecutiveAlliancepartner::where('alliancepartner_id', $request->alliancepartner_id)->first();
        if (!empty($hc_lead)) {
            $notification_to[] = $hc_lead->healthcoach_id;
        }
        if (!empty($admin)) {
            $notification_to[] = $admin->id;
        }
        if (!empty($coordinator)) {
            $notification_to[] = $coordinator->coordinator_id;
        }
        if (!empty($marketinghead)) {
            $notification_to[] = $marketinghead->mh_id;
        }
        if (!empty($marketingexecutive)) {
            $notification_to[] = $marketingexecutive->me_id;
        }
        foreach ($notification_to as $notification_to) {
            \App\Models\Notification::create([
                'user_id' => $notification_to,
                'message' => "New client enrolled - " . $request->name,
                'meta' => [
                    'client_id' => $user->id
                ]
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Account created succesfullyy',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate($this->rules());

        $data = $request->all();
        $data['dob'] = date('Y-m-d', strtotime($data['dob']));
        $data['user_id'] = $data['user_id'] ?? null;
        if (auth()->user()->role_id == 2) {
            $data['alliancepartner_id'] = auth()->user()->id;
        } else {
            $alianConsultant = DB::table('alian_consultants')->where('consultant_id', auth()->user()->id)->first();
            $data['alliancepartner_id'] = $alianConsultant->aliance_id;
        }


        $lead = Lead::create($data);
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Lead generated succesfullyy',
                'lead' => $lead
            ]);
        } else {
            return back()->with('success', 'Lead generated succesfully');
        }
    }

    public function website_store(Request $request)
    {
        $data = $request->all();
        $data['dob'] = date('Y-m-d', strtotime($data['dob']));
        $from = new DateTime($data['dob']);
        $to   = new DateTime('today');
        $data['age'] = $from->diff($to)->y;
        $data['user_id'] = $data['user_id'] ?? null;
        $data['alliancepartner_id'] =     109;
        $lead = Lead::create($data);
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Lead generated succesfullyy',
                'lead' => $lead
            ]);
        } else {
            return back()->with('success', 'Lead generated succesfully');
        }
    }

    public function update(Request $request, int $id)
    {
        $lead = Lead::find($id);

        if ($lead) {
            $request->validate($this->rules($id));
            $lead->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Lead updated succesfully',
                'lead' => $lead
            ]);
        }
        return response()->json([
            'success' => true,
            'message' => 'Lead not found in storage',
        ]);
    }

    public function destroy(int $id)
    {
        $lead = Lead::find($id);

        if ($lead) {

            $lead->delete();

            return response()->json([
                'success' => true,
                'message' => 'Lead deleted succesfully',
                'lead' => $lead
            ]);
        }
        return response()->json([
            'success' => true,
            'message' => 'Lead not found in storage',
        ]);
    }

    public function assignhc(Request $request)
    {
        $data = $request->all();
        $hc = User::find($request->healthcoach_id);
        $hc_lead = HealthcoachLead::create($data);
        return response()->json([
            'success' => true,
            'message' => 'Lead assigned to ' . $hc->name,
        ]);
    }


    public function assignprogram(Request $request)
    {
        $data = $request->all();
        $leadprogram = LeadProgram::create($data);
        $lead = Lead::find($data['lead_id']);
        $package = \App\Models\Package::find($data['program_id']);

        $mailmessage = (new MailMessage)
            ->line('Hai ' . $lead->name)
            ->line('New program assigned, below are the details.')
            ->line('Details:')
            ->line('Package Name: ' . $package->title . " program.")
            ->action('Click here to make the payment', url('/lead/programpayment', $leadprogram->id))
            ->line('Thank You')->render();
        Mail::send([], [], function ($message) use (&$mailmessage, $lead) {
            $message->to($lead->email)
                ->subject('Healthinfinit - Program Assignment')
                ->setBody($mailmessage, 'text/html'); // for HTML rich messages
        });

        // Update Status
        $lead->status = 'Payment link sent';
        $lead->save();
        return response()->json([
            'success' => true,
            'message' => 'Program assigned to lead',
        ]);
    }

    public function assignedprogram($id)
    {
        $leadprogram = LeadProgram::find($id);
        $package = \App\Models\Package::find($leadprogram->program_id);
        return response()->json([
            'success' => true,
            'package' => $package,
            'price' => $leadprogram->price,
            'plan' => $leadprogram->plan,
        ]);
    }

    public function getLeadStatus()
    {
        $status = DB::table('lead_status')->select('lead_status.status as id', 'lead_status.status as label')->get();
        return response()->json([
            'success' => true,
            'status' => $status,
        ]);
    }

    public function updatestatus(Request $request)
    {
        $lead = Lead::find($request->lead_id);
        $data = $request->all();
        $lead->status = $request->status;
        if (!empty($request->callback_date)) {
            $lead->callback_date = date('Y-m-d', strtotime($request->callback_date));
        }
        $lead->save();
        return response()->json([
            'success' => true,
            'message' => 'Status updated success',
        ]);
    }

    public function getQueriesList($id)
    {
        $queries = DB::table('lead_queries')->where('lead_id', $id)->get();
        return response()->json([
            'success' => true,
            'queries' => $queries,
        ]);
    }

    public function storequery(Request $request)
    {
        $data = $request->all();
        DB::table('lead_queries')->insert($data);
        return response()->json([
            'success' => true,
            'message' => 'Query inserted successfully!',
        ]);
    }
    public function storeRemark(Request $request)
    {
        $data = $request->all();
        Lead::where('id', $request->lead_id)->update(['remark' => $request->remark]);
        return response()->json([
            'success' => true,
            'message' => 'Remark updated successfully!',
        ]);
    }

    public function checkCreateLead()
    {
        $role = auth()->user()->role_id;
        $is_consultant = \App\Models\Role::where('id', $role)->first()->is_consultant;
        $createLead = '1';
        if ($is_consultant == '1') {
            $consultantinfo = \App\Models\UserDetail::where('user_id', auth()->user()->id)->first();
            $alianConsultant = \App\Models\AlianConsultant::where('consultant_id', auth()->user()->id)->pluck('aliance_id')->toArray();
            if ($consultantinfo->is_upgraded == '1' && !empty($alianConsultant)) {
                $createLead = '1';
            } else {
                $createLead = '0';
            }
        }
        return response()->json([
            'success' => true,
            'createLead' => $createLead,
        ]);
    }

    public function sendotp(Request $request)
    {
        $request->validate($this->rules());
        $mobile = $request->phone;
        $apiKey = urlencode('MzY2NzM4Nzk3ODMxNGM2ZDU0MzM2ZTZjMzE2ZTYyNTc=');

        $numbers = array($mobile);
        $sender = urlencode('HLTINF');
        $otp = rand(1000, 9999);
        $message = "Dear Customer, your OTP is " . $otp . ". Use this to verify your mobile - Healthinfinit";
        $numbers = implode(',', $numbers);
        $data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);

        $ch = curl_init('https://api.textlocal.in/send/');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        session(['leadotp' => $otp]);

        return response()->json([
            'success' => true,
        ]);
    }

    public function verifyotp(Request $request)
    {
        $otp = session('leadotp');
        if ($request->otp == $otp) {
            return response()->json([
                'success' => true,
            ]);
        } else {
            return response()->json([
                'success' => false,
            ]);
        }
    }
}
