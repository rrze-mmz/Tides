<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserCommentsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return Response
     */
    public function __invoke(Request $request)
    {
        $userComments = auth()->user()->comments()->where('type', 'frontend')->get();

        return view('frontend.myPortal.comments', ['comments' => $userComments]);
    }
}
