<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePortalSettings;
use App\Models\Setting;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Artisan;

class PortalSettingsController extends Controller
{
    /**
     * Display Portal settings
     *
     * @return Application|Factory|View
     */
    public function show(): Application|Factory|View
    {
        $setting = Setting::firstOrCreate(
            ['name' => 'portal'],
            [
                'name' => 'portal',
                'data' => [
                    'maintenance_mode' => false,
                ],
            ]
        );

        $setting->data = [
            'maintenance_mode' => $setting->data['maintenance_mode'] ?? 'false',
        ];

        return view('backend.settings.portal', ['setting' => $setting->data]);
    }

    /**
     * Update Portal settings
     *
     * @param  UpdatePortalSettings  $request
     * @return void
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
