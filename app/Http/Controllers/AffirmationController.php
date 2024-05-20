<?php

namespace App\Http\Controllers;

use App\Models\Affirmation;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RoleUser;
use App\Models\ClientReferral;

class AffirmationController extends Controller
{
    public function index( Request $request )
    {
        return response()->json([
            'affirmations' =>  Affirmation::limit($request->per_page)->offset(($request->page-1)*$request->per_page )->get(),
            'page' => $request->page,
            'total' => Affirmation::count(),
            'per_page' => $request->per_page
        ]);
    }

    public function show()
    {
        $role_id = auth()->user()->role_id;
        $referred_detail = '';

        if ($role_id == 3) {
            $referred_detail = ClientReferral::select('ref_to_u.id as referred_to_id','ref_to_u.name as referred_to_name','ref_by_u.name as referred_by_name', 'ref_to_ud.specialized_in as referred_to_specialized_in','ref_to_u.avatar')
            ->join('users as ref_to_u', 'ref_to_u.id', '=', 'client_referrals.referred_to')
            ->join('user_details as ref_to_ud', 'ref_to_ud.user_id', '=', 'client_referrals.referred_to')
            ->join('users as ref_by_u', 'ref_by_u.id', '=', 'client_referrals.referred_by')
            ->join('user_details as ref_by_ud', 'ref_by_ud.user_id', '=', 'client_referrals.referred_by')
            ->leftJoin('bookings', function($join) {
                $join
                ->on('bookings.user_id', '=', 'client_referrals.referred_to')
                ->on('bookings.booked_by', '=', 'client_referrals.client_id');
            })
            ->where('client_id', auth()->user()->id)
            ->whereNull('bookings.id')
            ->where('client_referrals.status', 'active')
            ->orderBy('client_referrals.id', 'DESC')
            ->first();
        }

        return response()->json([
            'success' => true,
            'affirmation' => Affirmation::inRandomOrder()->value('text'), 
            'referred_detail' => $referred_detail
        ]);

    }

    private function rules()
    {
        return [
            'text' => 'required'
        ];
    }

    public function store( Request $request )
    {
        $request->validate( $this->rules());

        $affirmation = new Affirmation;

        $affirmation->text = $request->text;

        $affirmation->save();

        return response()->json([
            'success' => true,
            'message' => 'Affirmation stored successfully'
        ]);

    }

    public function update( Request $request, int $id)
    {
        $request->validate( $this->rules());

        $affirmation = Affirmation::find($id);

        if( $affirmation )
        {

            $affirmation->text = $request->text;

            $affirmation->save();

            return response()->json([
                'success' => true,
                'message' => 'Affirmation updated successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Please provide valid input'
        ]);

    }

    public function destroy( int $id )
    {
        $affirmation = Affirmation::find($id);

        if( $affirmation )
        {
            $affirmation->delete();

            return response()->json([
                'success' => true,
                'message' => 'Affirmation deleted successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Please provide valid input'
        ]);
    }
}
