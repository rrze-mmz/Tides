<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class ApiTagsController extends Controller
{
    public function __invoke(Request $request)
    {
        return response()->json([
            "results" => Tag::where('name','like',$request['query'])->get()
        ]);
    }
}
