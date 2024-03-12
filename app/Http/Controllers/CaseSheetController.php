<?php

namespace App\Http\Controllers;

use App\Models\CaseSheet;
use Illuminate\Http\Request;

class CaseSheetController extends Controller
{
    public function index(int $id, Request $request)
    {
        $caseSheet = CaseSheet::where('client_id', $id)
            ->join('users as consultant', 'consultant.id', '=', 'case_sheets.user_id')
            ->join('users as client', 'client.id', '=', 'case_sheets.client_id')
            ->join('roles', 'roles.id', '=', 'consultant.role_id')
            ->orderBy('case_sheets.created_at', 'desc')
            ->select(
                'consultant.name as consultant',
                'consultant.avatar as consultant_avatar',
                'consultant.email as consultant_email',
                'roles.name as role',
                'client.email as client_email',
                'client.name as client',
                'client.avatar as client_avatar',
                'case_sheets.*'
            );
        $u1 = clone $caseSheet;
        return response()->json([
            'sheets' => $u1->limit($request->per_page)->offset(($request->page - 1) * $request->per_page)->get(),
            'page' => $request->page,
            'total' => $caseSheet->count(),
            'per_page' => $request->per_page
        ]);
        //return response()->json(['success' => true, 'sheets' => $caseSheets]);
    }

    private function rules()
    {
        return [
            'client' => 'required',
            'date' => ['required'],
            'chief_concern' => 'required',
            'client_feedback' => 'required',
            'associate_concern' => 'required',
            'plan_of_action' => 'required',
        ];
    }

    public function store(Request $request)
    {
        $request->validate($this->rules());

        $caseSheet = new CaseSheet();

        $caseSheet->user_id = auth()->user()->id;
        $caseSheet->client_id = $request->client;
        $caseSheet->date = $request->date;
        $caseSheet->chief_concern = $request->chief_concern;
        $caseSheet->client_feedback = $request->client_feedback;
        $caseSheet->associate_concern = $request->associate_concern;
        $caseSheet->plan_of_action = $request->plan_of_action;
        $caseSheet->save();

        return response()->json(['success' => true, 'message' => "Case Sheet Created Successfully"]);
    }
}
