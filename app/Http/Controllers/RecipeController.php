<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\RecipeUser;
use App\Models\Tag;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class RecipeController extends Controller
{
    public function index(Request $request)
    {
        $client = $request->client ?? auth()->user()->id;
        $filter = $request->filter ?? null;
        $recipies =  Recipe::select('recipes.*')
            ->when(auth()->user()->role_id === 3 || $request->client, function ($q) use ($client) {
                $q->join('recipe_users', 'recipe_users.recipe_id', '=', 'recipes.id')
                    ->where('recipe_users.user_id', $client)
                    ->join('users', 'users.id', '=', 'recipes.creator')
                    ->join('roles', 'roles.id', '=', 'users.role_id')
                    ->addSelect('users.name as consultant', 'roles.name as consultant_type');
            })->when(auth()->user()->role_id === 5, function ($q) {
                $q->where('recipes.creator', auth()->user()->id);
            })->when($filter, function ($q) use ($filter) {
                $q->where('recipes.name', 'like', "%" . $filter . "%");
            });
        $r1 = clone $recipies;
        return response()->json([
            'recipes' => $r1->limit($request->per_page)->offset(($request->page - 1) * $request->per_page)->get(),
            'page' => $request->page,
            'total' => $recipies->count(),
            'per_page' => $request->per_page,
            'filter' => $request->filter
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'unique:recipes,name'],
            'type' => ['required'],
            'instructions' => 'required',
            'image' => ['required', 'image'],
            'pdf' => ['required', 'mimes:pdf'],
            'tags.*' => 'required'
        ]);
        DB::beginTransaction();
        try {
            $tags = $this->getTags($request->tags);
            $recipe = new Recipe();
            $recipe->name = $request->name;
            $recipe->type = $request->type;
            $recipe->instructions = $request->instructions;
            $recipe->tags = $tags;
            $recipe->image = $request->file('image')->store('files/recipe/image');
            $recipe->pdf = $request->file('pdf')->store('files/recipe/pdf');
            $recipe->creator = auth()->user()->id;
            $recipe->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Recipe created succssfully', 'type' => 'success']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Something went wrong please try after some time', 'type' => 'error']);
        }
    }

    private function getTags($tags)
    {
        $tags = json_decode($tags, true);
        $tagNames = [];
        foreach ($tags as $tag) {
            Tag::updateOrCreate([
                'name' => $tag['name'],
            ], [
                'name' => $tag['name'],
                'created_by' => auth()->user()->id
            ]);
            $tagNames[] = $tag['name'];
        }
        return  Tag::whereIn('name', $tagNames)->select('id', 'name')->get()->toArray();
    }

    public function update(Request $request, int $id)
    {
        $request->validate(
            [
                'name' => ['required', 'unique:recipes,name,' . $id],
                'instructions' => 'required',
                'type' => 'required',
                'image' => ['nullable', 'image'],
                'pdf' => ['nullable', 'mimes:pdf'],
                'tags.*' => 'required'
            ]
        );

        $recipe = Recipe::find($id);
        if ($recipe) {
            $tags = $this->getTags($request->tags);
            $recipe->name = $request->name;
            $recipe->type = $request->type;
            $recipe->instructions = $request->instructions;
            $recipe->tags = $tags;
            if ($request->hasFile('image')) {
                if ($recipe->image) {
                    File::delete(public_path($recipe->image));
                }
                $recipe->image = $request->file('image')->store('files/recipe/image');
            }
            if ($request->hasFile('pdf')) {
                if ($recipe->pdf) {
                    File::delete(public_path($recipe->pdf));
                }
                $recipe->pdf = $request->file('pdf')->store('files/recipe/pdf');
            }
            $recipe->update();

            return response()->json(['success' => true, 'message' => 'Recipe updated succesfully', 'type' => 'success']);
        }
        return response()->json(['success' => false, 'message' => 'Recipe not found in storage', 'type' => 'danger']);
    }

    public function destroy(int $id)
    {
        $recipe = Recipe::find($id);
        if ($recipe) {;
            RecipeUser::where('recipe_id', $id)->delete();

            $recipe->delete();

            return response()->json(['success' => true, 'message' => 'Recipe deleted succesfully', 'type' => 'success']);
        }
        return response()->json(['success' => false, 'message' => 'Recipe not found in storage', 'type' => 'danger']);
    }
}
