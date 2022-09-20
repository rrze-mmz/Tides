<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PortalSettingsController extends Controller
{
    /**
     * Display Portal settings
     *
     * @return Application|Factory|View
     */
    public function show(): Application|Factory|View
    {
        $setting = Setting::portal();

        $setting->data = [
            'maintenance_mode' => $setting->data['maintenance_mode'] ?? 'false',
        ];

        return view('backend.settings.portal', ['setting' => $setting->data]);
    }

    /**
     * Update Portal settings
     *
     * @param Request $request
     * @return void
     */
    public function update(Request $request)
    {

    }
}
