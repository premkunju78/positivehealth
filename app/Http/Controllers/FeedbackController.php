<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function list()
    {
        $role = auth()->user()->role_id;
        $feedbacks = Feedback::join('users as client', 'client.id', '=', 'feedbacks.client_id')
            ->join('users as user', 'user.id', '=', 'feedbacks.user_id')
            ->join('workflow_schedules', 'workflow_schedules.id', '=', 'feedbacks.workflow_session_id')
            ->when($role, function ($q) use ($role) {
                if ($role == 3) {
                    $q->where('feedbacks.client_id', auth()->user()->id);
                } elseif ($role > 3) {
                    $q->where('feedbacks.user_id', auth()->user()->id);
                } elseif ($role == 2) {
                    $clients = \App\Models\AlianClient::where('aliance_id', auth()->user()->id)->pluck('client_id');
                    $q->whereIn('feedbacks.client_id', $clients);
                }
            })->select('user.id as user_id', 'user.name as user_name', 'client.id as client_id', 'client.name as client_name', 'feedbacks.rating as rating', 'workflow_schedules.title as session_title')->get();
        return response()->json(['success' => true, 'feedbacks' => $feedbacks]);
    }

    public function store(Request $request)
    {
        $feedback = new Feedback;
        $feedback->client_id = $request->client_id;
        $feedback->user_id = $request->user_id;
        $feedback->workflow_session_id = $request->id;
        $feedback->rating = $request->rating;
        $feedback->save();
        return response()->json(['success' => true, 'message' => 'Feedback submitted successfully']);
    }
}
