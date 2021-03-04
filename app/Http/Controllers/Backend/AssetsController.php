<?php

namespace App\Http\Controllers\Backend;

use App\Models\Clip;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AssetsController extends Controller
{

    /**
     * @param Clip $clip
     * @param Request $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
     */
    public function store(Clip $clip, Request $request): \Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'uploadedFile' => 'file|required'
        ]);

        $uploadedFile = $request->file('uploadedFile')->store('public/videos/');

        $clip->addAsset($uploadedFile);

        return redirect($clip->adminPath());
    }
}
