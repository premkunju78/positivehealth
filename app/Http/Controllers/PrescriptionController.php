<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrescriptionController extends Controller
{
   
    public function index( Request $request)
    {
        $role = auth()->user()->role_id;
        
        $id = $request->id;
        $client = $request->client ?? null;
        $prescriptions =  Prescription::select('prescriptions.*')
        ->when( $role === 3 , function($q) use($id){
            $q->join('users','users.id','=','prescriptions.user_id')
                ->leftJoin('user_details','users.id','=','user_details.user_id')
                ->addSelect('users.name as consultant','user_details.specialized_in')
                ->where( 'prescriptions.client_id', $id);
        }, function($q){
            $q->where('prescriptions.user_id',auth()->user()->id)
            ->join('users','users.id','=','prescriptions.client_id')
            ->addSelect('users.name as client','users.phone');
        })
        ->when($client, function($q) use($client){
            $q->where( 'prescriptions.client_id', $client);
        })
        ->where([
            'prescriptions.active' => 1,
        ])->orderBy('prescriptions.id','desc');

        $p1 = clone $prescriptions;

        return response()->json([
            'prescriptions' => $p1->limit($request->per_page)->offset(($request->page-1)*$request->per_page )->get(),
            'page' => $request->page,
            'total' => $prescriptions->count(),
            'per_page' => $request->per_page
        ]);
    }

    public function show(Request $request, int $id)
    {
        $role = auth()->user()->role_id;
        $client_id = $request->client_id ?? auth()->user()->id;

        $prescription = Prescription::where('prescriptions.id', $id)
        ->select('prescriptions.*')
        ->when( $role, function($q) use($client_id) {
            $q->join('users','users.id','=','prescriptions.user_id')
            ->join('users as client','client.id','=','prescriptions.client_id')
            ->leftJoin('user_details','users.id','=','user_details.user_id')
            ->leftJoin('user_details as client_detail','client_detail.user_id','=','prescriptions.client_id')
                ->addSelect(
                    'users.name as consultant','users.email as consultant_email','user_details.specialized_in','users.email','users.phone',
                    'user_details.specialization','user_details.specialized_in as consultant_specialized_in','user_details.clinic_name as consultant_clinic','user_details.pincode as consultant_pincode',DB::raw("concat(user_details.address,', ',user_details.city,'-',user_details.state,' ','India') as consultant_address"),'users.avatar','client.name as patient_name',
                    'user_details.logo','user_details.signature_img','prescriptions.data',
                    'client.email as patient_email','client.phone as patient_phone','user_details.dob','client_detail.gender as patient_gender'
                ) ->where( 'prescriptions.client_id', $client_id);
        })->first();

        return response()->json([
            'prescription' => $prescription,
            'success' => true
        ]);
    }

    private function rules()
    {
        return  [
            'client' => 'required',
            'prescriptions.*' => 'required',
            'instructions' => 'required'
        ];
    }

    public function store( Request $request )
    {
        $request->validate($this->rules());

        $prescription = new Prescription([
            'client_id' => $request->client,
            'user_id' => auth()->user()->id,
            'data' => $request->prescriptions,
            'instructions' => $request->instructions
        ]);

        $prescription->save();

        $prescription->no = 'PRESC-'. ($prescription->id > 9 ? $prescription->id: "0{$prescription->id}");
        
        $prescription->save();

        return response()->json([
            'success' => true,
            'message' => 'new prescription created successfully'
        ]);
    }

    public function update( Request $request, int $id )
    {
        $request->validate( [
            'prescriptions.*' => 'required',
            'instructions' => 'required'
        ]);

        $prescription = Prescription::find($id);

        if($prescription)
        {
            $prescription->update([
                'data' =>  $request->prescriptions,
                'instructions' => $request->instructions
            ]);

            return response()->json([
                'success' => true,
                'message' => 'prescription updated successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'prescription not found in storage'
        ]);
    }

    public function destroy( int $id )
    {

        $prescription = Prescription::find($id);

        if($prescription)
        {
            $prescription->update([
                'active' => 0
            ]);

            return response()->json([
                'success' => true,
                'message' => 'prescription deleted successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'prescription not found in storage'
        ]);
    }
}
