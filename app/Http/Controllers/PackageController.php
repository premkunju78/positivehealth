<?php

namespace App\Http\Controllers;

use App\Models\AlianClient;
use App\Models\Lead;
use App\Models\MarketingheadAlliancepartner;
use App\Models\Package;
use App\Models\PackageGroupMapping;
use App\Models\PackageUser;
use App\Models\User;
use App\Models\UserDetail;
use App\utils\Helpers;
use Illuminate\Support\Facades\Notification;
use App\Notifications\FreeSession;
use App\Notifications\Callback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $plan = $request->plan ?? null;
        $type = $request->type ?? null;
        $category = $request->category ?? null;
        $sort = ($request->sort == 'true') ?? null;
        $recent = ($request->recent == 'true') ?? null;
        $role = auth()->user()->role_id;

        $packages =  Package::when($recent, function ($q) {
            $q->orderBy('id', 'asc');
        })
            ->when($sort == true, function ($q) {
                $q->orderBy('title');
            })->when(($sort != true && !$recent), function ($q) {
                $q->orderBy('title', 'asc');
            })
            ->when($plan, function ($q) use ($plan) {
                $q->whereRaw('json_contains(plans, \'["' . $plan . '"]\')');
            })
            ->when($role, function ($q) use ($role) {
                if ($role == 2) {
                    $ids = PackageUser::where('user_id', auth()->user()->id)->pluck('package_id');
                    $q->whereIn('id', $ids);
                } elseif ($role == 3) {
                    $alliance_partners = \App\Models\AlianClient::where('client_id', auth()->user()->id)->pluck('aliance_id');
                    $ids = PackageUser::whereIn('user_id', $alliance_partners)->pluck('package_id');
                    $q->whereIn('id', $ids);
                }
            })
            ->when($type, function ($q) use ($type) {
                $q->where('type', $type);
            })->when($category, function ($q) use ($category) {
                $q->where('category', $category);
            });
        $p1 = clone $packages;

        return response()->json([
            'total' => $p1->count(),
            'page' => $request->page,
            'per_page' => $request->per_page,
            'packages' =>  $p1
                ->limit($request->per_page)
                ->offset(($request->page - 1) * $request->per_page)
                ->get(),
        ]);
    }

    public function details(Request $request)
    {
        $packages = AlianClient::join('package_users', 'package_users.user_id', '=', 'alian_client.client_id')
            ->join('users as partner', 'alian_client.aliance_id', '=', 'partner.id')
            ->join('users as client', 'alian_client.client_id', '=', 'client.id')
            ->join('packages', 'packages.id', '=', 'package_users.package_id')
            ->select(
                'packages.title as package',
                'partner.name as alliance_partner',
                'client.name as client_name',
                'client.phone as client_phone',
                'client.email as client_email',
                'package_users.transaction_date',
                'package_users.cost',
                'packages.type',
                'package_users.enrolled',
                'packages.payment_type',
                'packages.start_date',
                'packages.end_date',
                'package_users.data'
            );
        if (auth()->user()->role_id == 15) {
            $alliance_partners = MarketingheadAlliancepartner::where('mh_id', auth()->user()->id)->pluck('id');
            $packages->whereIn('alian_client.aliance_id', $alliance_partners);
        }
        $p1 = clone $packages;
        return response()->json([
            'packages' =>  $p1->limit($request->per_page)->offset(($request->page - 1) * $request->per_page)->get(),
            'page' => $request->page,
            'total' => $packages->count(),
            'per_page' => $request->per_page
        ]);
    }



    public function partners()
    {
        $partners = User::where('role_id', 2)
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->whereNotIn('users.id', PackageUser::where('package_id', request('package_id'))->pluck('user_id')->toArray())
            ->select('user_details.company_name as label', 'users.id')->get();

        return response()->json(['success' => 'true', 'partners' => $partners]);
    }

    public function users()
    {
        $partners = User::where('role_id', 2)
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->whereIn('users.id', PackageUser::where('package_id', request('package_id'))->pluck('user_id')->toArray())->pluck('users.id');
        $clients = User::where('role_id', 3)
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->whereIn('users.id', AlianClient::whereIn('aliance_id', $partners)->pluck('client_id')->toArray())
            ->whereNotIn('users.id', PackageUser::where('package_id', request('package_id'))->where('joined_date', '<>', NULL)->pluck('user_id')->toArray())
            ->select('users.name as label', 'users.id')->get();
        $leads = Lead::whereIn('alliancepartner_id', $partners)->select('name as label', 'id')->get();
        return response()->json(['success' => 'true', 'clients' => $clients, 'leads' => $leads]);
    }

    public function list()
    {
        $group = request('group') ?? null;
        $packages = Package::select('title as label', 'packages.id')->orderBy('title', 'asc')
            ->when($group, function ($q) use ($group) {
                $q->whereNotIn(
                    'packages.id',
                    PackageGroupMapping::where('group_id', $group)->pluck('package_id')->toArray()
                );
            })
            ->get();
        return response()->json(['success' => 'true', 'packages' => $packages]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'unique:packages,title'],
            'category' => 'required',
            'description' => 'required',
            'type' => 'required',
            'plans' => 'required',
            'payment_type' => 'required',
            'communication_type' => 'required',
            'sessions.*' => 'required'
        ]);


        $data = $request->all();

        $data = Helpers::storeFiles($request, $data);

        $package = new Package();
        $package->title = $request->title;
        $package->description = $request->description;
        $package->category = $request->category;
        $package->type = $request->type;
        $package->creator = auth()->user()->id;
        $package->image = $data['image'] ?? null;
        $package->payment_type = $data['payment_type'] ?? null;
        $package->communication_type = $data['communication_type'] ?? null;
        $package->sessions = json_decode($data['sessions'], true);
        $package->plans = json_decode($data['plans'], true);
        $package->cost = json_decode($data['cost'], true);
        $package->months = json_decode($data['months'], true);
        $package->start_date = $request->start_date ? date('Y-m-d H:i', strtotime($request->start_date)) : null;
        $package->end_date = $request->end_date ? date('Y-m-d H:i', strtotime($request->end_date)) : null;
        $package->save();

        return response()->json(['success' => true, 'message' => 'Program created succssfully', 'type' => 'success']);
    }

    public function show($id)
    {
        if (in_array(auth()->user()->role_id, [2])) {
            $package = Package::where('packages.id', $id)
                ->join('package_users', 'packages.id', '=', 'package_users.package_id')
                ->where('package_users.user_id', auth()->user()->id)
                ->select('packages.*', 'package_users.cost', 'package_users.transaction_id', 'package_users.id as package_user_id', 'package_users.enrolled', 'package_users.data')
                ->first();
        } else if (in_array(auth()->user()->role_id, [3])) {
            $package = Package::where('packages.id', $id)
                ->join('package_users', 'packages.id', '=', 'package_users.package_id')
                ->where('package_users.user_id', auth()->user()->id)
                ->select('packages.*', 'package_users.cost', 'package_users.transaction_id', 'package_users.id as package_user_id', 'package_users.enrolled', 'package_users.data')
                ->first();
            if (empty($package)) {
                $alliance_partner = AlianClient::where('client_id', auth()->user()->id)->first();
                $package = Package::where('packages.id', $id)
                    ->join('package_users', 'packages.id', '=', 'package_users.package_id')
                    ->where('package_users.user_id', $alliance_partner->aliance_id)
                    ->select('packages.*', 'package_users.cost', 'package_users.transaction_id', 'package_users.id as package_user_id', 'package_users.enrolled', 'package_users.data')
                    ->first();
            }
        } else {
            $package = Package::find($id);
        }
        $request_callback_data = [];
        if (!empty($package->request_callback)) {
            foreach ($package->request_callback as $key => $c) {
                $request_callback_data[$package->plans[$key]] = $c;
            }
        } else {
            foreach ($package->plans as $key => $plan) {
                $request_callback_data[$plan] = 'No';
            }
        }
        $userInfo = UserDetail::where('user_id', auth()->user()->id)->first();
        if (auth()->user()->role_id == 2) {
            $ap_costs = PackageUser::where('package_id', $id)->join('users', 'package_users.user_id', '=', 'users.id')->where('users.role_id', 2)->join('user_details', 'package_users.user_id', '=', 'user_details.user_id')->where('package_users.user_id', auth()->user()->id)->select('user_details.company_name as ap_name', 'package_users.*')->get();
        } else {
            $ap_costs = PackageUser::where('package_id', $id)->join('users', 'package_users.user_id', '=', 'users.id')->join('user_details', 'package_users.user_id', '=', 'user_details.user_id')->where('users.role_id', 2)->select('user_details.company_name as ap_name', 'package_users.*')->get();
        }

        if ($package) {
            return response()->json(['success' => true, 'package' => $package, 'type' => 'success', 'request_callback_data' => $request_callback_data, 'ap_costs' => $ap_costs, 'userInfo' => (!empty($userInfo)) ? $userInfo : []]);
        }
        return response()->json(['success' => false, 'message' => 'Program not found in storage', 'type' => 'danger']);
    }

    public function planslist($id)
    {
        $package = Package::find($id);
        if ($package) {
            $plans = array();
            foreach ($package->plans as $plan) {
                $plans[] = array('id' => $plan, 'label' => $plan, 'price' => $plan);
            }
            $alliance_partners = array();
            $planusers = PackageUser::join('users', 'package_users.user_id', '=', 'users.id')->where('package_id', $id)->where('users.role_id', 2)->select('users.*')->get();
            foreach ($planusers as $planuser) {
                $alliance_partners[] = array('id' => $planuser->id, 'label' => $planuser->name);
            }
            return response()->json(['success' => true, 'plans' => $plans, 'alliance_partners' => $alliance_partners, 'type' => 'success']);
        }
        return response()->json(['success' => false, 'message' => 'Program not found in storage', 'type' => 'danger']);
    }
    public function getPrice(Request $request)
    {
        $lead = \App\Models\Lead::find($request->lead_id);
        $planusers = PackageUser::join('users', 'package_users.user_id', '=', 'users.id')->where('package_id', $request->package_id)->where('users.role_id', 2)->where('package_users.user_id', $lead->alliancepartner_id)->select('package_users.*')->first();
        if ($planusers) {
            foreach ($planusers->data as $index => $data) {
                if ($index == $_REQUEST['plan']) {
                    return response()->json(['success' => true, 'price' => $data]);
                }
            }
        }
        return response()->json(['success' => false, 'message' => 'Program not assigned to alliance partner yet!', 'type' => 'danger']);
    }

    public function update(Request $request, int $id)
    {
        $request->validate(
            [
                'title' => ['required', 'unique:packages,title,' . $id],
                'category' => 'required',
                'description' => 'required',
                'type' => 'required',
                'plans' => 'required',
                'payment_type' => 'required',
                'communication_type' => 'required',
                'sessions.*' => 'required'
            ]
        );

        $data = $request->all();

        $data = Helpers::storeFiles($request, $data);

        $package = Package::find($id);

        if ($package) {
            $package->title = $request->title;
            $package->description = $request->description;
            $package->category = $request->category;
            $package->type = $request->type;
            $package->image = $data['image'] ??  $package->image;
            $package->payment_type = $data['payment_type'] ?? null;
            $package->communication_type = $data['communication_type'] ?? null;
            $package->sessions = json_decode($data['sessions'], true);
            $package->plans = json_decode($data['plans'], true);
            $request_callback_data = json_decode($data['request_callback'], true);
            $package->request_callback = array_map('strval', $request_callback_data);
            $package->cost = json_decode($data['cost'], true);
            $package->months = json_decode($data['months'], true);
            $package->start_date = $request->start_date ? date('Y-m-d H:i', strtotime($request->start_date)) : null;
            $package->end_date = $request->end_date ? date('Y-m-d H:i', strtotime($request->end_date)) : null;
            $package->update();

            return response()->json(['success' => true, 'message' => 'Program updated succesfully', 'type' => 'success']);
        }
        return response()->json(['success' => false, 'message' => 'Program not found in storage', 'type' => 'danger']);
    }

    public function destroy(int $id)
    {
        $package = Package::find($id);
        if ($package) {
            PackageGroupMapping::where('package_id', $id)->delete();

            PackageUser::where('package_id', $id)->delete();

            $package->delete();

            return response()->json(['success' => true, 'message' => 'Program deleted succesfully', 'type' => 'success']);
        }
        return response()->json(['success' => false, 'message' => 'Program not found in storage', 'type' => 'danger']);
    }

    public function groupPackages()
    {
        $packages = Package::whereNoIn(
            'id',
            PackageGroupMapping::where('group_id', request('group_id')->pluck('package_id')->toArray())
        )->select('title', 'id')->get();

        return response()->json(['success' => 'true', 'packages' => $packages]);
    }

    public function myPackages(Request $request)
    {
        if (in_array(auth()->user()->role_id, [2, 3])) {

            $plan = $request->plan ?? null;
            $type = $request->type ?? null;
            $sort = $request->sort ?? null;
            $recent = $request->recent ?? null;
            $enrolled = $request->enrolled ?? false;

            $packages = Package::join('package_users', 'packages.id', '=', 'package_users.package_id')
                ->where('package_users.user_id', auth()->user()->id)
                ->when($request->id, function ($q) {
                    $q->whereDate('packages.created_at', '>=', date('Y-m-d'));
                })
                ->when($recent, function ($q) {
                    $q->orderBy('packages.id', 'desc');
                })
                ->when($sort, function ($q) {
                    $q->orderBy('packages.title');
                })
                ->when($plan, function ($q) use ($plan) {
                    $q->where('packages.duration', $plan);
                })
                ->when($type, function ($q) use ($type) {
                    $q->where('packages.type', $type);
                })
                ->when($enrolled, function ($q) {
                    $q->where('package_users.enrolled', '1');
                }, function ($q) {
                    $q->where('package_users.enrolled', false);
                })
                ->select('packages.*', 'package_users.cost', 'package_users.transaction_id', 'package_users.id as package_user_id', 'package_users.enrolled', 'package_users.data as data','package_users.start_date as start_date','package_users.end_date as end_date')
                ->orderby('packages.id', 'desc');
            $p1 = clone $packages;
            $packages_array = $p1->get();
            foreach ($packages_array as $key => $package) {
                $usersessions = [];
                // if (!empty($package->data)) {
                //     $userplans = array_keys(json_decode($package->data, true));
                //     foreach ($userplans as $userplan) {
                //         $plankey = (string) array_search($userplan, $package->plans);
                //         $usersessions[$userplan] = (!empty($package->sessions) && $plankey != null) ? $package->sessions[$plankey] : '';
                //     }
                // }
                // $packages_array[$key]->sessions = $usersessions;
                $plan = (!empty($package->data)) ? array_key_first(json_decode($package->data, true)) : '';
                $pkey = (string) array_search($plan, $package->plans);
                $sessions = (!empty($package->sessions) && $pkey != null) ? $package->sessions[$pkey] : '';
                $packages_array[$key]->sessions = $sessions;
                $packages_array[$key]->plan = $plan;
            }
            return response()->json([
                'success' => true,
                'packages' => $packages_array,
                'page' => $request->page,
                'total' => $packages->count(),
                'per_page' => $request->per_page
            ]);
        }
        return response()->json(['success' => false, 'message' => 'Unauthorized']);
    }

    public function enroll(int $id)
    {
        $mapping =  PackageUser::find($id);
        if ($mapping) {
            $mapping->enrolled = true;
            $mapping->save();

            return response()->json(['success' => true, 'message' => 'Program enrolled successfully']);
        }
        return response()->json(['success' => false, 'message' => 'Unauthorized']);
    }

    public function bookFreeSession(Request $request)
    {

        $data = $request->all();
        $packageInfo = Package::where('id', $data['id'])->first();
        $userInfo = User::where('id', auth()->user()->id)->first();

        Notification::route('mail', "admin@prajanaawelltech.com")->notify(new FreeSession($userInfo, $packageInfo));

        return response()->json(['success' => true, 'data' => $userInfo, 'message' => 'Thank you, We would get back to you within 48 hours']);
    }
    public function callBackReq(Request $request)
    {
        $data = $request->all();
        $packageInfo = Package::where('id', $data['id'])->first();
        $userInfo = User::where('id', auth()->user()->id)->first();

        Notification::route('mail', "admin@prajanaawelltech.com")->notify(new Callback($userInfo, $packageInfo));

        return response()->json(['success' => true, 'data' => $userInfo, 'message' => 'Thank you, We would get back to you within 48 hours']);
    }
}
