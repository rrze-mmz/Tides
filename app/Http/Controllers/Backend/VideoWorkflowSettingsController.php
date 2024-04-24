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

        $setting->data = [
            'url' => $setting->data['url'] ?? 'localhost:8080',
            'username' => $setting->data['username'] ?? 'admin',
            'password' => $setting->data['password'] ?? 'username',
            'archive_path' => $setting->data['archive_path'] ?? '/opencast/archive/mh_default_org',
            'default_workflow_id' => $setting->data['default_workflow_id'] ?? 'fast',
            'upload_workflow_id' => $setting->data['upload_workflow_id'] ?? 'fast',
            'theme_id_top_right' => $setting->data['theme_id_top_right'] ?? '500',
            'theme_id_top_left' => $setting->data['theme_id_top_left'] ?? '501',
            'theme_id_bottom_left' => $setting->data['theme_id_bottom_left'] ?? '502',
            'theme_id_bottom_right' => $setting->data['theme_id_bottom_right'] ?? '503',
            'assistant_group_name' => $setting->data['assistant_group_name'] ?? 'ROLE_GROUP_TIDES_ASSISTANTS',
        ];

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

        return to_route('settings.opencast.show');
    }
}
