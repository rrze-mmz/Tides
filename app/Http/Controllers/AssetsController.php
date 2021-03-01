<?php

namespace App\Http\Controllers;

use App\Models\Clip;
use Illuminate\Http\Request;

class AssetsController extends Controller
{
    public function store(Clip $clip, Request $request)
    {
        $uploadedFile = $request->file('uploadedFile')->store('public/videos/');

        $clip->addAsset($uploadedFile);

        return redirect($clip->path());
    }
}
