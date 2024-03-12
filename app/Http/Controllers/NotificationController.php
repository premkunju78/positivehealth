<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index( $id )
    {
        $notifications = Notification::where([
            'user_id' => $id,
            'status' => 0
        ])->get();

        return response()->json([
            'success' => true,
            'notifications' => $notifications
        ]);
    }
}
