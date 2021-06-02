<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Clip;
use App\Services\WowzaService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;

class TriggerSmilFilesController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Clip $clip
     * @param WowzaService $wowzaService
     * @return RedirectResponse
     * @throws \DOMException
     * @throws AuthorizationException
     */
    public function __invoke(Clip $clip, WowzaService $wowzaService): RedirectResponse
    {
        $this->authorize('edit', $clip);

        $wowzaService->createSmilFiles($clip);

        session()->flash('flashMessage', $clip->title.' smil files created successfully ');

        return back();
    }
}
