<?php

namespace App\Http\Controllers\Backend;

use App\Models\Clip;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AssetsController extends Controller
{
    public function store(Clip $clip, Request $request)
    {
        $uploadedFile = $request->file('uploadedFile')->store('public/videos/');

        $clip->addAsset($uploadedFile);

        return redirect($clip->path());
    }
}
