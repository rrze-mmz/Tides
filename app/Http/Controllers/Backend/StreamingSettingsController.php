<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateStreamingSettings;
use App\Models\Setting;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class StreamingSettingsController extends Controller
{
    /**
     * Display streaming settings
     */
    public function show(): Application|Factory|View
    {
        $setting = Setting::streaming();

        return view('backend.settings.streaming', ['setting' => $setting->data]);
    }

    public function update(UpdateStreamingSettings $request)
    {
        $setting = Setting::streaming();

        $setting->data = $request->validated();

        $setting->save();

        return to_route('settings.streaming.show');
    }
}
