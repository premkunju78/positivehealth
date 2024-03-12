<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use DB;

class AvailabilityController extends Controller
{
    public function index()
    {
        $id = request('id');
        $availability = Availability::whereDate('start', '>=', date('Y-m-d'))
                        ->when( $id, function($q) use($id) {
                            $q->where('user_id', $id);
                        }, function($q){
                            $q->where('user_id', auth()->user()->id);
                        })
                       ->get();
            

        return response()->json($availability);
    }

    public function rules()
    {
        return [
            'startTime' => 'required',
            'endTime' => 'required',
            'frequency' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'startTime.required' => 'Start time field is required',
            'endTime.required' => 'End time field is required'
        ];
    }

    public function show(int $id)
    {
        return response()->json(Availability::select('availabilities.*','users.name as consultant_name', 'roles.name AS consultant_type','user_details.cost')
        ->selectRaw('DATE_FORMAT(start,"%H:%i:%s") as startTime')
        ->join('users', 'availabilities.user_id', '=', 'users.id')
        ->join('user_details', 'user_details.user_id', '=', 'users.id')
        ->join('roles', 'roles.id', '=', 'users.role_id')
       ->selectRaw('DATE_FORMAT(end,"%H:%i:%s") as endTime')
       ->selectRaw('start as startDate')
       ->selectRaw('end as endDate')
       ->selectRaw('IF(allDay = "1", "true", "false") as allDay')->find($id));
    }

    public function store(Request $request)
    {
        $request->validate($this->rules(), $this->messages());

        $startDate = date('Y-m-d', strtotime($request->startDate));

        $endDate = date('Y-m-d', strtotime($request->endDate." + 1 days"));

        // $startTime = date('Y-m-d H:i:s', strtotime( $date.' '. $request->startTime ));
        //
        // $endTime = date('Y-m-d H:i:s', strtotime(  $date.' '.$request->endTime ));

        $title = date('h:i a', strtotime($request->startTime)). '-'.date('h:i a', strtotime($request->endTime));

        $availableTimeslot = DB::table('availabilities')
        ->where('user_id', auth()->user()->id)
        ->whereDate('start','>=', $request->startDate)
        ->whereDate('start','<=', $request->endDate)->select('start', 'end')->get();

        $loopDate = date('Y-m-d', strtotime($request->startDate));

        do {
            $start = date('Y-m-d H:i:s', strtotime($loopDate.' '.$request->startTime));
            $end = date('Y-m-d H:i:s', strtotime($loopDate.' '. $request->endTime));
            $DuplicateSlotCount = 0;
            // Check for the Timeslot clash
            foreach ($availableTimeslot as $key => $value) {
              $cur_start = Carbon::parse($start);
              if($cur_start->between(Carbon::parse($value->start), Carbon::parse($value->end), true) ){
                $DuplicateSlotCount++;
              }
            }
            //Log::info("date:- ".date('N', strtotime($loopDate)));
            if(!in_array(date('N', strtotime($loopDate)), $request->selectedDays) && !$DuplicateSlotCount){
            $availability = new Availability();
            $availability->title = $title;
            $availability->start = $start;
            $availability->end = $end;
            $availability->date = $loopDate;
            $availability->allDay = 0;
            $availability->user_id = auth()->user()->id;
            $availability->extendedProps = ['calendar' => 'Availability'];
            $availability->frequency = $request->frequency;
            $availability->group = $request->group;
            $availability->attendee = $request->attendee;
            $availability->save();
          }
            $loopDate = date('Y-m-d', strtotime($loopDate." + 1 days"));

        } while ($loopDate != $endDate);
        if($DuplicateSlotCount)
          return response()->json(['success'=>false,'message'=>'Availability Clash found']);

        return response()->json(['success'=>true,'message'=>'availability saved successfully']);
    }


    public function update(Request $request, int $id)
    {
        $request->validate($this->rules(), $this->messages());


        $availability = Availability::find($id);


        if ($availability) {

            $availability->start = date('Y-m-d H:i:s', strtotime($request->startDate.' '.$request->startTime));
            $availability->end = date('Y-m-d H:i:s', strtotime($request->endDate.' '.$request->endTime));
            $availability->title = date('h:i a', strtotime($request->startTime)). '-'.date('h:i a', strtotime($request->endTime));
            $availability->frequency = $request->frequency;
            $availability->group = $request->group;
            $availability->attendee = $request->attendee;
            $availability->save();

            return response()->json(['success'=>true,'message'=>'availability updated successfully']);
        }

        return response()->json(['success'=>false,'message'=>'data not available in master']);
    }

    public function destroy(int $id)
    {
        $availability = Availability::find($id);

        if ($availability) {
            $availability->delete();

            return response()->json(['success'=>true,'message'=>'availability deleted successfully']);
        }

        return response()->json(['success'=>false,'message'=>'data not available in master']);
    }


    public function list( Request $request)
    {
        $filters = $request->filters ?? false;
        
        $availabilities = Availability::join('users','users.id','=','availabilities.user_id')
        ->join('roles','roles.id','=','users.role_id')
        ->when( $filters && !empty($filters), function($q) use($filters) {
            if(isset($filters['role'])){
                $q->where('roles.id',$filters['role']);
            } 
            if(isset($filters['from']) & isset($filters['to'])){
                $q->whereBetween('availabilities.start',[$filters['from'],$filters['to']." 23:59:00"]);
            }
            
        })
        ->select(
            'availabilities.start','availabilities.end','availabilities.frequency',
            'users.name','roles.name as role'
        );

        $a1 = clone $availabilities;
        return response()->json([
            'success' => true,
            'availabilities' => $a1->limit($request->per_page)->offset(($request->page-1)*$request->per_page )->get(),
            'page' => $request->page,
            'total' => $availabilities->count(),
            'per_page' => $request->per_page
        ]);        
    }
}
