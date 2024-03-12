<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagsController extends Controller
{
    public function index()
    {
        $tag = request('tag') ?? null;

        $tags = Tag::when($tag, function($q) use($tag){
                    $q->where('name','like',"%{$tag}%");
                })->select('id','name')->get();

        return response()->json( $tags);
    }

}
