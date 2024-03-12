<?php

namespace App\Http\Controllers;

use App\Models\Affirmation;
use Illuminate\Http\Request;

class AffirmationController extends Controller
{
    public function index( Request $request )
    {
        return response()->json([
            'affirmations' =>  Affirmation::limit($request->per_page)->offset(($request->page-1)*$request->per_page )->get(),
            'page' => $request->page,
            'total' => Affirmation::count(),
            'per_page' => $request->per_page
        ]);
    }

    public function show()
    {
        return response()->json([
            'success' => true,
            'affirmation' => Affirmation::inRandomOrder()->value('text')
        ]);

    }

    private function rules()
    {
        return [
            'text' => 'required'
        ];
    }

    public function store( Request $request )
    {
        $request->validate( $this->rules());

        $affirmation = new Affirmation;

        $affirmation->text = $request->text;

        $affirmation->save();

        return response()->json([
            'success' => true,
            'message' => 'Affirmation stored successfully'
        ]);

    }

    public function update( Request $request, int $id)
    {
        $request->validate( $this->rules());

        $affirmation = Affirmation::find($id);

        if( $affirmation )
        {

            $affirmation->text = $request->text;

            $affirmation->save();

            return response()->json([
                'success' => true,
                'message' => 'Affirmation updated successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Please provide valid input'
        ]);

    }

    public function destroy( int $id )
    {
        $affirmation = Affirmation::find($id);

        if( $affirmation )
        {
            $affirmation->delete();

            return response()->json([
                'success' => true,
                'message' => 'Affirmation deleted successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Please provide valid input'
        ]);
    }
}
