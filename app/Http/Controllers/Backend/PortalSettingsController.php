<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePortalSettings;
use App\Models\Setting;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PortalSettingsController extends Controller
{
    /**
     * Display Portal settings
     */
    public function show(): Application|Factory|View
    {
        $setting = Setting::firstOrCreate(
            ['name' => 'portal'],
            [
                'name' => 'portal',
                'data' => [config('settings.portal')],
            ]
        );

        $setting->data = [
            'maintenance_mode' => $setting->data['maintenance_mode'] ?? false,
            'allow_user_registration' => $setting->data['allow_user_registration'] ?? false,
            'feeds_default_owner_name' => $setting->data['feeds_default_owner_name'] ?? 'Tides',
            'feeds_default_owner_email' => $setting->data['feeds_default_owner_email'] ?? 'itunes@tides.com',
            'player_show_article_link_in_player' => $setting->data['player_show_article_link_in_player'] ?? false,
            'player_article_link_url' => $setting->data['player_article_link_url'] ?? 1,
            'player_article_link_text' => $setting->data['player_article_link_text'] ?? '',
            'clip_generic_poster_image_name' => $setting->data['clip_generic_poster_image_name'] ?? '',
            'show_dropbox_files_in_dashboard' => $setting->data['show_dropbox_files_in_dashboard'] ?? true,
        ];

        return view('backend.settings.portal', ['setting' => $setting->data]);
    }

    /**
     * Update Portal settings
     *
     * @return RedirectResponse
     */
    public function update(UpdatePortalSettings $request)
    {
        //existing setting
        $setting = Setting::portal();
        $validated = $request->validated();

        $setting->data = $validated;
        $setting->save();

        return to_route('settings.portal.show');
    }
}
