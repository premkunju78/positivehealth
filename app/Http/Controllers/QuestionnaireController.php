<?php

namespace App\Http\Controllers;

use App\Models\Questionnaire;
use App\Models\QuestionnaireUser;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use PDO;

class QuestionnaireController extends Controller
{
    public function index(Request $request)
    {
        $mappingCheck = auth()->user()->role_id === 3;
        $isConsultant = auth()->user()->role->is_consultant;
        $assessments = $request->assessments ?? 'false';

        $id = $request->id ?? null;

        if ($id) {
            $questionnaire = Questionnaire::select('questionnaires.id', 'questionnaires.description', 'questionnaires.name', 'questionnaires.type', 'questionnaires.creator')->groupBy('questionnaires.id')
                ->where('questionnaires.active', 'YES')
                ->leftJoin('questionnaire_users', 'questionnaire_users.questionnaire_id', '=', 'questionnaires.id')
                ->addSelect('questionnaire_users.result', 'questionnaire_users.id as questionnaire_user_id')
                ->when($assessments,  function ($q) use ($assessments) {
                    if ($assessments == 'true') {
                        $q->where('questionnaires.type', 'Assessment Form');
                    } else {
                        $q->where('questionnaires.type', '<>', 'Assessment Form');
                    }
                })
                ->when($id, function ($q) use ($id) {
                    $q->where('questionnaire_users.user_id', $id);
                });
        } else {
            $questionnaire = Questionnaire::select('questionnaires.id', 'questionnaires.description', 'questionnaires.name', 'questionnaires.type', 'questionnaires.creator')->groupBy('questionnaires.id')
                ->where('questionnaires.active', 'YES')
                ->leftJoin('questionnaire_users', 'questionnaire_users.questionnaire_id', '=', 'questionnaires.id')
                ->addSelect('questionnaire_users.result', 'questionnaire_users.id as questionnaire_user_id')
                ->when(auth()->user()->role_id === 3, function ($q) {
                    $q->where('questionnaire_users.user_id',  auth()->user()->id);
                })
                ->when(auth()->user()->role_id === 2,  function ($q) {
                    $q->where('questionnaires.creator',  auth()->user()->id);
                })
                ->when($isConsultant == '1',  function ($q) {
                    $authorization = UserDetail::where('user_id', auth()->user()->id)->first()->authorization;
                    if ($authorization != 'Internal') {
                        $q->where('questionnaires.creator',  auth()->user()->id);
                    } else {
                        $q->where('questionnaire_users.user_id',  auth()->user()->id);
                    }
                })->when($assessments,  function ($q) use ($assessments) {
                    if ($assessments == 'true') {
                        $q->where('questionnaires.type', 'Assessment Form');
                    }
                });
        }
        $response = [
            'data' =>  $questionnaire->limit($request->per_page)->offset(($request->page - 1) * $request->per_page)->get(),
            'page' => 1,
            'total' => $questionnaire->count(),
            'per_page' => $request->per_page
        ];
        return response()->json([
            'success' => true,
            'questionnaires' => $response
        ]);
    }

    public function assessmenthistory(Request $request)
    {
        $history = QuestionnaireUser::where('user_id', $request->id)->join('questionnaires', 'questionnaires.id', '=', 'questionnaire_users.questionnaire_id')
            ->where('questionnaires.type', 'Assessment Form')
            ->where('questionnaire_users.result', '<>', NULL)
            ->select('questionnaires.id', 'questionnaires.description', 'questionnaires.name', 'questionnaires.type', 'questionnaires.creator', 'questionnaire_users.result', 'questionnaire_users.updated_at', 'questionnaire_users.id as questionnaire_user_id');
        $response = [
            'data' =>  $history->limit($request->per_page)->offset(($request->page - 1) * $request->per_page)->get(),
            'page' => 1,
            'total' => $history->count(),
            'per_page' => $request->per_page
        ];
        return response()->json([
            'success' => true,
            'questionnaires' => $response
        ]);
    }

    public function show(int $id)
    {
        $questionnaire = Questionnaire::find($id);
        if ($questionnaire) {
            $array = $questionnaire->content;
            array_walk_recursive($array, 'self::myFunc');
            return response()->json(['success' => true, 'content' => $array, 'name' => $questionnaire->name, 'type' => $questionnaire->type, 'description' => $questionnaire->description]);
        }

        return response()->json(['success' => false, 'message' => 'data not found in storage']);
    }
    public function myFunc(&$item, $key)
    {
        $item = (is_null($item)) ? "" : $item;
    }

    private function rules($id = null)
    {
        if (!empty($id)) {
            return [
                //'description' => 'required',
                'name' => ['required', 'unique:questionnaires,name,' . $id],
                'content' => 'required'
            ];
        }
        return [
            // 'description' => 'required',
            'name' => ['required', 'unique:questionnaires,name'],
            'content' => 'required'
        ];
    }

    private function messages()
    {
        return ['content.required' => 'please create form first'];
    }

    public function store(Request $request)
    {
        $request->validate($this->rules(), $this->messages());

        $questionnaire = new Questionnaire();

        $questionnaire->name = $request->name;
        $questionnaire->type = $request->type;
        $questionnaire->description = trim($request->description);

        $questionnaire->content = $request->content;

        $questionnaire->creator = auth()->user()->id;

        $questionnaire->save();

        return response()->json([
            'success' => true,
            'message' => "Questionnaire $request->name saved successfully"
        ]);
    }
    public function update(Request $request, $id)
    {
        $request->validate($this->rules($id), $this->messages());

        $questionnaire = Questionnaire::find($id);

        $questionnaire->name = $request->name;
        $questionnaire->type = $request->type;
        $questionnaire->description = trim($request->description);

        $questionnaire->content = $request->content;

        $questionnaire->creator = auth()->user()->id;

        $questionnaire->save();

        return response()->json([
            'success' => true,
            'message' => "Questionnaire $request->name saved successfully"
        ]);
    }

    public function destroy(int $id)
    {
        $questionnaire = Questionnaire::where('id', $id)->first();

        if (!$questionnaire->users()->whereNotNull('result')->exists()) {
            QuestionnaireUser::where('questionnaire_id', $id)->delete();

            $questionnaire->delete();
        } else {
            $questionnaire->active = 'NO';

            $questionnaire->save();
        }

        return response()->json([
            'success' => true,
            'message' => "Questionnaire deleted successfully"
        ]);
    }

    public function storeResult(Request $request)
    {
        $client_id = (!empty($request->client_id)) ? $request->client_id : auth()->user()->id;
        if ($request->new && $request->new == 'true') {
            $questionnaire = QuestionnaireUser::where('questionnaire_id', $request->id)->where('user_id', $client_id)
                ->first();
            $questionnaire = new QuestionnaireUser();
            $questionnaire->questionnaire_id = $request->id;
            $questionnaire->user_id = $client_id;
            $questionnaire->result = $request->result;
            $questionnaire->save();
            return response()->json(['success' => true, 'message' => 'Questionnaire response stored successfully']);
        } else {
            $questionnaire = QuestionnaireUser::where('questionnaire_id', $request->id)->where('user_id', $client_id)
                ->first();
            if ($questionnaire) {
                $questionnaire->result = $request->result;
                $questionnaire->save();
                return response()->json(['success' => true, 'message' => 'Questionnaire response stored successfully']);
            }
        }
        return response()->json(['success' => false, 'message' => 'data not found in storage']);
    }

    public function getResult(int $id)
    {
        $result = QuestionnaireUser::find($id);

        if ($result) {
            return response()->json(['success' => true, 'content' => $result->result]);
        }
        return response()->json(['success' => false, 'message' => 'data not found in storage']);
    }

    public function updateResult(Request $request, int $id)
    {
        $questionnaire = QuestionnaireUser::find($id);

        if ($questionnaire) {
            $questionnaire->result = $request->result;
            $questionnaire->save();

            return response()->json(['success' => true, 'message' => 'questionnaire response updated successfully']);
        }
        return response()->json(['success' => false, 'message' => 'data not found in storage']);
    }
}
