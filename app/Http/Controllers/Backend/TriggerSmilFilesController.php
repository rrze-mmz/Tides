<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Clip;
use App\Services\WowzaService;
use DOMException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;

class TriggerSmilFilesController extends Controller
{
    /**
     * Generates a wowza smil file for a clip
     *
     *
     * @throws AuthorizationException|DOMException
     */
    public function __invoke(Clip $clip, WowzaService $wowzaService): RedirectResponse
    {
        $wowzaService->createSmilFile($clip);

        session()->flash('flashMessage', "{$clip->title} smil files created successfully");

        return back();
    }
}
