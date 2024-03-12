<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    public function index( Request $request)
    {
        $reminders =  Reminder::select('reminders.*')
        ->when( auth()->user()->role_id === 1 , function($q){
            $q->join('users','users.id','=','reminders.user_id')
            ->join('roles','roles.id','=','users.role_id')
            ->addSelect('users.name','roles.name as role');
        },function($q){
            $q->where('user_id', auth()->user()->id);
        })->orderBy('reminders.status');
        $r1 = clone $reminders;
        return response()->json([
            'reminders' =>  $r1->limit($request->per_page)->offset(($request->page-1)*$request->per_page )->get(),
            'page' => $request->page,
            'total' => $reminders->count(),
            'per_page' => $request->per_page
        ]);
    }

    private function rules()
    {
        return [
            'title' => 'required',
            'date' => 'required',
            'notes' => 'nullable'
        ];
    }


    public function store( Request $request )
    {
        $request->validate($this->rules());

        $reminder = new Reminder();
        $reminder->date = date('Y-m-d H:i:s',strtotime( $request->date) );
        $reminder->title = $request->title;
        $reminder->notes = $request->notes;
        $reminder->user_id = auth()->user()->id;
        $reminder->save();

        return response()->json(['success' => true,'message' => 'Reminder added succssfully','type' => 'success']);
    }

    public function update( Request $request , int $id )
    {
        $request->validate($this->rules());

        $reminder = Reminder::find($id);
        if( $reminder) 
        {
            $reminder->date = date('Y-m-d H:i:s',strtotime( $request->date) );
            $reminder->title = $request->title;
            $reminder->notes = $request->notes;
            $reminder->update();
    
            return response()->json(['success' => true, 'message' => 'reminder updated succesfully','type' => 'success']);
        }
        return response()->json(['success' => false,'message' => 'Reminder not found in storage','type' => 'danger']);
    }


    public function destroy(int $id)
    {
        $reminder = Reminder::find($id);
        if( $reminder) 
        {

            $reminder->delete();
    
            return response()->json(['success' => true, 'message' => 'Reminder deleted succesfully','type' => 'success']);
        }
        return response()->json(['success' => false,'message' => 'Reminder not found in storage','type' => 'danger']);
    }

    public function updatestatus(Request $request)
    {
        $reminder = Reminder::find($request->id);
        $reminder->status = $request->status;
        $reminder->save();
        return response()->json([
            'success' => true,
            'message' => 'Reminder status updated successfully!',
        ]);
    }
}
