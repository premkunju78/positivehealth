<?php

namespace App\Http\Controllers;

use App\Models\DietPlan;
use App\Models\PlanUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DietPlanController extends Controller
{
    public function index(Request $request)
    {
        $client = $request->client ?? auth()->user()->id;
        $plans =  DietPlan::select('diet_plans.*')
            ->when(auth()->user()->role_id === 3 || $request->client, function ($q) use ($client) {
                $q->join('plan_users', 'plan_users.diet_plan_id', '=', 'diet_plans.id')
                    ->where('plan_users.user_id', $client)
                    ->join('users', 'users.id', '=', 'diet_plans.creator')
                    ->join('roles', 'roles.id', '=', 'users.role_id')
                    ->addSelect('users.name as consultant', 'roles.name as consultant_type');
            });
        $p1 = clone $plans;
        return response()->json([
            'plans' =>  $p1->limit($request->per_page)->offset(($request->page - 1) * $request->per_page)->get(),
            'page' => $request->page,
            'total' => $plans->count(),
            'per_page' => $request->per_page
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'no' => ['required', 'unique:diet_plans,no'],
            'title' => ['nullable', 'unique:diet_plans,title'],
            'instructions' => 'nullable',
            'image' => ['nullable', 'mimes:pdf'],
            'pdf' => ['required', 'mimes:pdf']
        ]);


        $dietPlan = new DietPlan();
        $dietPlan->no = $request->no;
        $dietPlan->title = $request->title;
        $dietPlan->instructions = $request->instructions;
        $dietPlan->image = (!empty($request->file('image'))) ? $request->file('image')->store('/filesdiet-plan/image') : null;
        $dietPlan->pdf = $request->file('pdf')->store('files/diet-plan/pdf');
        $dietPlan->creator = auth()->user()->id;
        $dietPlan->save();

        if ($request->client && $request->client != "null") {
            PlanUser::create([
                'diet_plan_id' =>  $dietPlan->id,
                'user_id' => $request->client,
                'expiry_date' => $request->expiry_date
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Diet Plan created succssfully', 'type' => 'success']);
    }

    public function update(Request $request, int $id)
    {
        $request->validate(
            [
                'no' => ['required', 'unique:diet_plans,no,' . $id],
                'title' => ['nullable', 'unique:diet_plans,title,' . $id],
                'instructions' => 'nullable',
                'image' => ['nullable', 'mimes:pdf'],
                'pdf' => ['nullable', 'mimes:pdf']
            ]
        );

        $dietPlan = DietPlan::find($id);
        if ($dietPlan) {
            $dietPlan->no = $request->no;
            $dietPlan->title = $request->title;
            $dietPlan->instructions = $request->instructions;
            if ($request->hasFile('image')) {
                if ($dietPlan->image) {
                    File::delete(public_path($dietPlan->image));
                }
                $dietPlan->image = $request->file('image')->store('/files/diet-plan/image');
            }
            if ($request->hasFile('pdf')) {
                if ($dietPlan->pdf) {
                    File::delete(public_path($dietPlan->pdf));
                }
                $dietPlan->pdf = $request->file('pdf')->store('/files/diet-plan/pdf');
            }

            $dietPlan->update();

            return response()->json(['success' => true, 'message' => 'Diet Plan updated succesfully', 'type' => 'success']);
        }
        return response()->json(['success' => false, 'message' => 'Diet Plan not found in storage', 'type' => 'danger']);
    }

    public function destroy(int $id)
    {
        $dietPlan = DietPlan::find($id);
        if ($dietPlan) {;
            PlanUser::where('diet_plan_id', $id)->delete();

            $dietPlan->delete();

            return response()->json(['success' => true, 'message' => 'Diet Plan deleted succesfully', 'type' => 'success']);
        }
        return response()->json(['success' => false, 'message' => 'Diet Plan not found in storage', 'type' => 'danger']);
    }
}
