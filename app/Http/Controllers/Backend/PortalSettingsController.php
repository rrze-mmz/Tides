<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePortalSettings;
use App\Models\Setting;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;

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
                'data' => [
                    'maintenance_mode' => config('tides.maintenance_mode'),
                    'allow_user_registration' => config('tides.allow_user_registration'),
                    'feeds_default_owner_name' => config('tides.feeds_default_owner_name'),
                    'feeds_default_owner_email' => config('tides.feeds_default_owner_email'),
                ],
            ]
        );

        $setting->data = [
            'maintenance_mode' => $setting->data['maintenance_mode'] ?? false,
            'allow_user_registration' => $setting->data['allow_user_registration'] ?? false,
            'feeds_default_owner_name' => $setting->data['feeds_default_owner_name'] ?? 'Tides',
            'feeds_default_owner_email' => $setting->data['feeds_default_owner_email'] ?? 'itunes@tides.com',
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

        //user updated setting
//        if ($setting->data['maintenance_mode'] !== $validated['maintenance_mode']) {
//            //temporary disabled because not working as expected
//            $call = $validated['maintenance_mode'] ? 'down' : 'up';
//            Artisan::call($call);
//        }

        $setting->data = $validated;
        $setting->save();

        return to_route('settings.portal.show');
    }
}
