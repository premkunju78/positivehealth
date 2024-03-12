<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use App\Services\Vi;
use DateTime;
use DateTimeZone;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;

class MeetingController extends Controller
{
    public function createLink( Request $request )
    {
        $frequency = (strtotime($request->timeslot_to) -strtotime($request->timeslot_from))/60;
      
        $url = config('services.enablex.url').'/'.config('services.enablex.version');

        $timeslot_from = new DateTime( $request->timeslot_from);

        $timeslot_from = $timeslot_from->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');

        $response = Http::withHeaders([
            'Content-Type'=> 'application/json',
            'Authorization' => 'Basic ' . base64_encode(config('services.enablex.app_id') . ":" . config('services.enablex.app_key')),
        ])->post("$url/rooms", [
            "name" => $request->name,
            "owner_ref" => auth()->user()->name.'-'.auth()->user()->id,
            "settings" => [
              "description" => $request->description,
              "mode" => "group",
              "scheduled" => false,
              "adhoc" => true,
              "duration" => (int)$frequency,
              "moderators" => "1",
              "participants" => "1",
              "quality" => "SD",
              "auto_recording" => false,
            ]
        ])->json();
        


        if( isset( $response['room']))
        {
            $meeting_url = config('services.enablex.domain');
            $booking = Booking::find($request->id);
            $booking->participant_link =  $meeting_url.'/'.$response['room']['room_id'].'?name='.$booking->name;
            $booking->host_link = $meeting_url.'/'.'host/'. base64_encode($response['room']['room_id'] . "-" . config('services.enablex.app_id')).'?name='.auth()->user()->name;;
            $booking->save();

            return response()->json(['success' => true, 'message'=>'video link generated successfully']);
        }

        return response()->json(['success' => false, 'message'=>  $response['error'][0] ?? 'Error generating video link']);
    }


    public function callNow( Request $request )
    {
        $no1 = auth()->user()->phone;
        if( $request->booked_by == auth()->user()->id ) {
            $user = User::find($request->user_id);
        }  else {
            $user = User::find($request->booked_by);
        }
        if( $user ) 
        {
            $no2= $user->phone;

            ( new Vi)->call( $no1, $no2 );

            return response()->json(['success' => true, 'message' => 'call connected successfully ']);
        }
        throw new Exception('Something went wrong while initiating call');
    }
}
