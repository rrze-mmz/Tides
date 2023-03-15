<?php

namespace App\Observers;

use App\Models\Setting;

class SettingObserver
{
    /**
     * Handle the Setting "saving" event.
     *
     * @return void
     */
    public function saved(Setting $setting)
    {
        session()->flash('flashMessage', str($setting->name)->ucfirst().' settings  '.__FUNCTION__.' successfully');
    }
}
