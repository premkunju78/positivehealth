<?php

namespace App\Http\Controllers;

use App\Models\Test;
use Illuminate\Http\Request;

class TestController extends Controller
{
	public function index( Request $request)
    {
        $tests =  Test::where([
            'tests.active' => 1,
            'test_categories.active' => 1
        ])
        ->join('test_categories','test_categories.id','=','tests.test_category_id')
        ->select('tests.*','test_categories.name as category')
        ->orderBy('tests.created_at','desc')
        ->orderBy('tests.test_category_id');
                    
        $t1 = clone $tests;
        return response()->json([
            'testItems' => $t1->limit($request->per_page)->offset(($request->page-1)*$request->per_page )->get(),
            'page' => $request->page,
            'total' => $tests->count(),
            'per_page' => $request->per_page
        ]);
    }

    public function list( int $testCategory)
    {
        $categories = Test::where('active',1)
					->where('test_category_id', $testCategory)
					->select('name','id')->get();
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
                'unique:tests,name,'.$id
            ]
        ];
    }

    public function store( Request $request )
    {
        $request->validate( $this->rules() );

        $test = new Test([
            'name' => $request->name,
			'test_category_id' => $request->test_category
        ]);

        $test->save();

        return response()->json([
            'success' => true,
            'message' => 'Test added successfully'
        ]);
    }

    public function update( Request $request, int $id )
    {
        $request->validate( $this->rules( $id ) );

        $test = Test::find($id);

        if( $test)
        {
            $test->name = $request->name;
			$test->test_category_id = $request->test_category;
            $test->update();

            return response()->json([
                'success' => true,
                'message' => 'Test updated successfully'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Test not found in storage'
        ]);
    }

    public function destroy( int $id )
    {
        $test = Test::find($id);

        if( $test)
        {
            $test->update(['active' => 0]);
            
            return response()->json([
                'success' => true,
                'message' => 'Test deleted successfully'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Test not found in storage'
        ]);
    }
}
