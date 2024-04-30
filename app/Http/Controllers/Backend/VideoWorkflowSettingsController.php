<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateVideoWorkflowSettings;
use App\Models\Setting;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class VideoWorkflowSettingsController extends Controller
{
    /**
     * Display Opencast settings
     */
    public function show(): Application|Factory|View
    {
        $setting = Setting::opencast();

        return view('backend.settings.workflow', ['setting' => $setting->data]);
    }

    /**
     * Update Opencast settings
     */
    public function update(UpdateVideoWorkflowSettings $request): RedirectResponse
    {

        $setting = Setting::opencast();

        $setting->data = $request->validated();
        $setting->save();

        return to_route('settings.workflow.show');
    }
}
