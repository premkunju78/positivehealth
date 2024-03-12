<?php

namespace App\Http\Controllers;

use App\Models\HtmlQuestionnaire;
use App\Models\HtmlQuestionnaireUser;
use Illuminate\Http\Request;

class HtmlquestionnaireController extends Controller
{
    public function index(Request $request)
    {
        $role = auth()->user()->role_id;
        $id = $request->id ?? null;
        $questionnaire = HtmlQuestionnaire::select('html_questionnaires.*')
            ->when(($role == 3), function ($q) {
                $q->join('html_questionnaire_users', 'html_questionnaire_users.questionnaire_id', '=', 'html_questionnaires.id')->where('html_questionnaire_users.user_id', '=', auth()->user()->id)
                    ->addSelect(
                        'html_questionnaire_users.result',
                        'html_questionnaire_users.id as questionnaire_user_id'
                    );
            })->when(($id), function ($q) use($id) {
                $q->join('html_questionnaire_users', 'html_questionnaire_users.questionnaire_id', '=', 'html_questionnaires.id')->where('html_questionnaire_users.user_id', '=', $id)
                    ->addSelect(
                        'html_questionnaire_users.result',
                        'html_questionnaire_users.id as questionnaire_user_id'
                    );
            });
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

    public function show(int $id)
    {
        $questionnaire = HtmlQuestionnaire::find($id);
        if ($questionnaire) {
            return response()->json(['success' => true, 'content' => $questionnaire->content]);
        }

        return response()->json(['success' => false, 'message' => 'data not found in storage']);
    }
    public function list(Request $request)
    {
        $id = $request->id ?? null;
        $questionnaire = HtmlQuestionnaire::select('html_questionnaires.id as id', 'html_questionnaires.name as label')->get();
        if (!empty($request->client_id)) {
            $assigned_qs = HtmlQuestionnaireUser::where('user_id', $request->client_id)->pluck('questionnaire_id');
        }
        return response()->json([
            'success' => true,
            'questionnaires' => $questionnaire,
            'assigned_qs' => (!empty($assigned_qs)) ? $assigned_qs : array()
        ]);
    }
    private function rules()
    {
        return [
            'description' => 'required',
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
        $questionnaire = QuestionnaireUser::where('questionnaire_id', $request->id)->where('user_id', auth()->user()->id)
            ->first();

        if ($questionnaire) {
            $questionnaire->result = $request->result;
            $questionnaire->save();

            return response()->json(['success' => true, 'message' => 'questionnaire response stored successfully']);
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

    public function assignClient(Request $request)
    {
        foreach ($request->q_ids as $id) {
            HtmlQuestionnaireUser::updateOrcreate(
                ['user_id' => $request->client_id, 'questionnaire_id' => $id],
                ['user_id' => $request->client_id, 'questionnaire_id' => $id]
            );
        }
        HtmlQuestionnaireUser::where('user_id', $request->client_id)->whereNotIn('questionnaire_id', $request->q_ids)->delete();
        return response()->json(['success' => true, 'message' => 'questionnaire assigned updated successfully']);
    }

    public function submit_html_questinnaires(Request $request)
    {
        $user_id = $request->user_id;
        $questionnaire_id = $request->questionnaire_id;
        HtmlQuestionnaireUser::where('user_id', $user_id)->where('questionnaire_id', $questionnaire_id)->update(['result' => json_encode($request->all())]);
        echo '<div class="alert alert-success">Questionnaire Submitted successfully <style>.alert.alert-success {
            background: #81d481;
            padding: 13px;
            color: #333;
            border-radius: 3px;
            border: 2px solid green;
            text-align: center;
            width: auto;
            display: table;
            margin: 0 auto;
            margin-top: 30px;
        }</style></div>';
        exit;
    }
}
