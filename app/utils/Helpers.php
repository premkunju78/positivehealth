<?php
namespace App\utils;

use App\Models\Availability;
use App\Models\Booking;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Helpers
{

    //  Helper Multiple Filter
    public function filter($model, $columns, $param, $request)
    {
        // Loop through the fields checking if they've been input, if they have add
        //  them to the query.
        $fields = [];
        for ($key = 0; $key < count($columns); $key++) {
            $fields[$key]['param'] = $param[$key];
            $fields[$key]['value'] = $columns[$key];
        }

        foreach ($fields as $field) {
            $model->where(function ($query) use ($request, $field, $model) {
                return $model->when($request->filled($field['value']),
                    function ($query) use ($request, $model, $field) {
                        $field['param'] = 'like' ?
                        $model->where($field['value'], 'like', "{$request[$field['value']]}")
                        : $model->where($field['value'], $request[$field['value']]);
                    });
            });
        }

        // Finally return the model
        return $model;
    }

    //  Check If Hass Permission Show All records
    public function Show_Records($model)
    {
        $Role = Auth::user()->roles()->first();
        $ShowRecord = Role::findOrFail($Role->id)->inRole('record_view');

        if (!$ShowRecord) {
            return $model->where('user_id', '=', Auth::user()->id);
        }
        return $model;
    }


    public static function storeFiles( Request $request, array $data )
    {
        
        if( $request->hasFile('logo') )
        {
            $ext = $request->logo->getClientOriginalExtension();
            if( in_array(strtolower($ext), ['png','svg','jpg','jpeg','gif'])) {

                $data['logo'] = $request->logo->store('user/logo');
            }
        }
        if( $request->hasFile('banner_img') )
        {
            $ext = $request->banner_img->getClientOriginalExtension();
            if( in_array(strtolower($ext),  ['png','svg','jpg','jpeg','gif'])) {

                $data['banner_img'] = $request->banner_img->store('user/banner');
            }
        }
        if( $request->hasFile('signature_img') )
        {
            $ext = $request->signature_img->getClientOriginalExtension();
            if( in_array(strtolower($ext),  ['png','svg','jpg','jpeg','gif'])) {

                $data['signature_img'] = $request->signature_img->store('user/signature');
            }
        }
        if( $request->hasFile('certificates') )
        { 
            $data['certificates'] =  $data['certificates'] ?? [];
            foreach(  $request->certificates as $certificate ){
                $ext = $certificate->getClientOriginalExtension();
                if( in_array(strtolower($ext),  ['png','svg','pdf','jpg','jpeg','gif'])) {
                   array_push( $data['certificates'],$certificate->store('user/certificates'));
                }
            }
          
        }
        if( $request->hasFile('cancelled_cheque') )
        {
            $ext = $request->cancelled_cheque->getClientOriginalExtension();
            if( in_array(strtolower($ext),  ['png','svg','jpg','jpeg','gif'])) {

                $data['cancelled_cheque'] = $request->cancelled_cheque->store('user/signature');
            }
        }
        if( $request->hasFile('image') )
        {
            $ext = $request->image->getClientOriginalExtension();
            if( in_array(strtolower($ext),  ['png','svg','jpg','jpeg','gif'])) {

                $data['image'] = $request->image->store('program/images');
            }
        }
        if( $request->hasFile('idproof') )
        {
            $ext = $request->idproof->getClientOriginalExtension();
            if( in_array(strtolower($ext), ['png','svg','jpg','jpeg','gif'])) {

                $data['idproof'] = $request->idproof->store('user/idproof');
            }
        }
        foreach( $data as &$d )
        {
            if( $d === "null" || $d === "undefined"){
                $d = null;
            }
        }
        return $data;
    }


    public static function getSlots( int $id , $date ,  $format="H:i" )
    {
        $bookings = Booking::whereDate('timeslot_from', $date )
        ->where('user_id',$id)->get()->toArray();
       
        $availabilities = Availability::where('user_id',$id)->whereDate('date',$date)->get();

        $no_of_slots = $availabilities->count();

        $slots = [];

        if( $no_of_slots > 0 )
        {

            $from_val = $to_val = [];


            $currentTime = date('Y-m-d H:i');
         
            foreach( $availabilities as $k => $availability )
            {
                $frequency = $availability->frequency;

                $startTime = date('H:i', strtotime($availability->start));

                $endTime = date('H:i', strtotime($availability->end));
                
                $startDateTime = date('Y-m-d H:i', strtotime($date." ". $startTime));

                $endDateTime = date('Y-m-d H:i', strtotime($date." ". $endTime));
              
                if( $currentTime >  $endDateTime )
                {
                    continue;
                }

                if( $currentTime > $startDateTime)
                {
                    if( date('i', strtotime($currentTime)) > 30 ){
                        $startTime = date('H', strtotime($currentTime)) + 1 .":00";
                    } else {
                        $startTime = date('H', strtotime($currentTime)).":30";
                    }
                }
               
                $startDateTime = date('Y-m-d H:i', strtotime($date." ". $startTime));
        
                try
                {
                    while( $startDateTime < $endDateTime  )
                    {
                        
                        $newStart = date('Y-m-d H:i' , strtotime("+ $frequency minutes",strtotime($startDateTime)) );
                        $available = true;
                        $startTime =  date('H:i',strtotime($startDateTime));
                        
                        if( count( $bookings ))
                        {
                            foreach( $bookings as $key => $booking )
                            {
                                if( date('H:i', strtotime($booking['timeslot_from']) ) === $startTime ){
                                    $available = false;
                                    unset($bookings[$key]);
                                    break;
                                }
                            }
                        }
                       
                    
                        if( $available )
                        {
                            $slots[] = [
                                'from' => date($format,strtotime($startDateTime)),
                                'to' => date($format,strtotime($newStart)),
                            ];
                        
                        }

                        $startDateTime = $newStart ;
                    
                    }
        
                } 
                catch( \Exception $e)
                {
                    \Log::info('Exception while creating slots', ['excetion' => $e]);
                    return $slots;
                }
            }
        }
        return $slots;
    }

}
