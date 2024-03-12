<?php

namespace App\Http\Controllers;

use App\Models\AlliancePartner;
use App\Models\UserDetail;
use App\utils\Helpers;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function updateGeneral(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => ['required', 'unique:users,phone,' .  $user->id],
            'address' => 'required',
            'city' => 'required',
            'pincode' => 'required',
            'logo' => 'sometimes',
            'signature_img' => 'sometimes',
            'banner_img' => 'sometimes',
            'dob' => 'sometimes',
            'gender' => 'required',
            'occupation' => 'sometimes',
            'guard_mob' => 'sometimes',
            'state' => 'required',
            'company_name' => 'sometimes',
            'contact_person' => 'sometimes',
            'company_contact' => 'sometimes',
            'website_name' => 'nullable',
            'url' => 'sometimes',
            'group' => 'sometimes',
            'type' => 'sometimes',
            'welcome_message' => 'sometimes',
            'cost' => 'sometimes',
            'clinic_name' => 'nullable',
            'terms_condition' => 'required',
        ]);


        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->name = $request->first_name . ' ' . $request->last_name;
        $user->phone = $request->phone;




        if ($request->hasFile('avatar')) {
            $ext = $request->avatar->getClientOriginalExtension();
            if (in_array(strtolower($ext), ['png', 'svg', 'jpg', 'jpeg'])) {

                $user->avatar = $request->avatar->store('user/avatar');
            }
        }


        if ($user->role_id >= 3) {
            $user->profile_updated = 1;
        }

        $user->save();

        if ($user->role_id > 1) {
            $data = $request->all();

            $data = Helpers::storeFiles($request, $data);
            if ($data['idproof'] === null) {
                unset($data['idproof']);
            }
            $data['dob'] = (!empty($request->dob)) ? date('Y-m-d', strtotime($request->dob)) : null;
            $data['service_areas'] = (!empty($request->service_areas)) ? json_decode($request->service_areas, true) : null;
            UserDetail::where('user_id', $user->id)->first()->update($data);
        }
        return response()->json(['success' => true, 'message' => "general detail updated successfully"]);
    }

    public function updateInformation(Request $request)
    {
        $user = auth()->user();
        if ($user->role_id === 2) // Alliance partners
        {
            $request->validate([
                'bank_name' => 'required',
                'account_no' => 'required',
                'ifsc_code' => 'required'
            ]);
        } else if ($user->role_id === 3) //Client
        {
            $request->validate([
                'dob' => 'required',
                'gender' => 'required',
                'guard_mob' => 'required',
                'address' => 'required',
                'city' => 'required',
                'pincode' => 'required',
                'state' => 'required',
            ]);
        } else {  // Consultants
            $request->validate([
                'cost' => 'required',
                'specialized_in' => 'required',
                'qualification' => 'required',
                'certificates' => 'nullable',
                'bank_name' => 'required',
                'account_no' => 'required',
                'ifsc_code' => 'required',
                'cancelled_cheque' => 'nullable',
                'signature_img' => 'nullable',
            ], [
                'specialized_in.required' => 'specializaion field is required'
            ]);
        }

        $user->profile_updated = 1;
        $user->save();

        $userDetail = UserDetail::where('user_id', $user->id)->first();



        $data = $request->all();


        $data['certificates'] = $userDetail->certificates;
        $data['cancelled_cheque'] = $userDetail->cancelled_cheque;
        $data['signature_img'] = $userDetail->signature_img;
        $data['banner_img'] = $userDetail->banner_img;
        $data['logo'] = $userDetail->logo;
        $data = Helpers::storeFiles($request, $data);
        $data['dob'] = (!empty($request->dob)) ? date('d-m-Y', strtotime($request->dob)) : NULL;
        $userDetail->update($data);

        return response()->json(['success' => true, 'message' => "user information updated successfully"]);
    }

    public function updateBio(Request $request)
    {
        $request->validate([
            'bio' => 'required',
            'aim' => 'required',
            'languages.*' => 'required',
            'skills.*' => 'required'
        ]);

        $userDetail = UserDetail::where('user_id', auth()->user()->id)->first();

        $userDetail->bio = $request->bio;

        $userDetail->aim = $request->aim ?? null;

        $userDetail->experience = $request->experience ?? null;

        if ($request->skills) {
            $userDetail->skills = $request->skills;
        }


        if ($request->languages) {
            $userDetail->languages = $request->languages;
        }

        $userDetail->save();

        return response()->json(['success' => true, 'message' => "user bio updated successfully"]);
    }


    public function removeCertificate(int $index)
    {
        $id = request('id') ?? auth()->user()->id;
        $userDetail = UserDetail::where('user_id', $id)->first();

        $certificate = $userDetail->certificates;

        unset($certificate[$index]);

        $userDetail->certificates = $certificate;

        $userDetail->save();

        return response()->json(['success' => true, 'message' => "certificates updated successfully"]);
    }
}
