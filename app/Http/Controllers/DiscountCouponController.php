<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DiscountCoupon;
use App\Models\EventTypes;
use Illuminate\Http\Request;
use DB;
use Auth;
use Session;

class DiscountCouponController extends Controller
{
    public function index(Request $request)
    {
        $discountCoupon = DiscountCoupon::when(auth()->user()->role_id != 1, function ($q) {
            $q->where('user_id', auth()->user()->id);
        })->join('users', 'users.id', '=', 'discount_coupons.user_id')
            ->select('discount_coupons.*', 'users.name as created_by');
        $d1 = clone  $discountCoupon;

        return response()->json([
            'coupons' =>  $d1->limit($request->per_page)->offset(($request->page - 1) * $request->per_page)->get(),
            'page' => $request->page,
            'total' =>  $discountCoupon->count(),
            'per_page' => $request->per_page
        ]);
    }

    public function show(int $id)
    {
        $discountCoupon = DiscountCoupon::find($id);
        if ($discountCoupon) {
            return response()->json([
                'success' => true,
                'coupon' => $discountCoupon
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'data not found in master'
        ]);
    }

    public function list()
    {
        $role=auth()->user()->role_id;
        $discountCoupon = DiscountCoupon::when($role, function ($q) use($role) {
            if($role==2){
                $coordinators=DB::table('coordinator_alliancepartners')->where('alliancepartner_id',auth()->user()->id)->pluck('coordinator_id');
                $couponIds=DB::table('discount_users')->whereIn('user_id',$coordinators)->pluck('coupon_id');
                $q->whereIn('id', $couponIds);
            } else if($role==3){
                $alliancepartner=DB::table('alian_client')->where('client_id',auth()->user()->id)->pluck('aliance_id');
                $coordinators=DB::table('coordinator_alliancepartners')->whereIn('alliancepartner_id',$alliancepartner)->pluck('coordinator_id');
                $couponIds=DB::table('discount_users')->whereIn('user_id',$coordinators)->pluck('coupon_id');
                $q->whereIn('id', $couponIds);
            }
        })->select('discount_coupons.*')->get();

        return response()->json([
            'coupons' =>  $discountCoupon,
        ]);
    }

    public function store(Request $request, DiscountCoupon $discountCoupon)
    {

        $request->validate($this->rules());

        $data = $request->all();

        $data['user_id'] = auth()->user()->id;

        $discountCoupon->fill($data);

        $discountCoupon->save();

        return response()->json([
            'success' => true,
            'message' => 'Discount Coupon generated successfully',
            'coupon' => $discountCoupon
        ]);
    }

    public function rules($id = '')
    {
        return  [
            'discount_code' => [
                'required',
                'unique:discount_coupons,discount_code,' . $id . ',id,user_id,' . auth()->user()->id
            ],
            'discount_value' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ];
    }


    public function update(Request $request, int $id)
    {
        $request->validate($this->rules($id));

        $data = $request->all();

        $discountCoupon = DiscountCoupon::find($id);

        $discountCoupon->fill($data);

        $discountCoupon->update();

        return response()->json([
            'success' => true,
            'message' => 'Discount Coupon updated successfully',
            'coupon' => $discountCoupon
        ]);
    }

    public function destroy(DiscountCoupon $discountCoupon)
    {
        if (auth()->user()->id === $discountCoupon->user_id) {
            $discountCoupon->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Discount Coupon deleted successfully',
        ]);
    }


    public function validateCouponCode()
    {
        $code = request('code') ?? null;

        if ($code) {
            $coupon = DiscountCoupon::whereDate('start_date', '<=', date('Y-m-d'))
                ->whereDate('end_date', '>=', date('Y-m-d'))
                ->where('discount_code', $code)
                ->first();
            if ($coupon) {
                return response()->json(['success' => true, 'coupon' => $coupon]);
            }
        }
        return response()->json(['success' => false, 'message' => 'Invalid coupon code']);
    }
}
