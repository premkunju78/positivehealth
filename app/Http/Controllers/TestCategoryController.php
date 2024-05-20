<?php

namespace App\Http\Controllers;

use App\Models\TestCategory;
use Illuminate\Http\Request;

class TestCategoryController extends Controller
{
    public function index( Request $request)
    {
        $category =  TestCategory::where('active',1);
        if ($request->type) {
            $category->where('type', $request->type);
        }

        $c1 = clone $category;
        return response()->json([
            'categories' => $c1->limit($request->per_page)->offset(($request->page-1)*$request->per_page )->get(),
            'page' => $request->page,
            'total' => $category->count(),
            'per_page' => $request->per_page
        ]);
    }

    public function list()
    {
        $categories = TestCategory::where('active',1)->select('name as label','id')->get();
        return response()->json([
            'categories' => $categories,
            'success' => true
        ]);
    }

    private function rules( $id = '')
    {
        return [
            'name' => [
                'required',
                'unique:test_categories,name,'.$id
            ]
        ];
    }

    public function store( Request $request )
    {
        $request->validate( $this->rules() );

        $category = new TestCategory([
            'name' => $request->name,
            'type' => $request->type
        ]);

        $category->save();

        return response()->json([
            'success' => true,
            'message' => 'category added successfully'
        ]);
    }

    public function update( Request $request, int $id )
    {
        $request->validate( $this->rules( $id ) );

        $category = TestCategory::find($id);

        if( $category)
        {
            $category->name = $request->name;
            $category->type = $request->type;
            $category->update();

            return response()->json([
                'success' => true,
                'message' => 'category updated successfully'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'category not found in storage'
        ]);
    }

    public function destroy( int $id )
    {
        $category = TestCategory::find($id);

        if( $category)
        {
            if( $category->test->count() )
            {
                $category->test()->update(['active' => 0]);
                $category->update(['active' => 0]);
            }
            else
            {
                $category->delete();
            }
            
            return response()->json([
                'success' => true,
                'message' => 'category deleted successfully'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'category not found in storage'
        ]);
    }
}
