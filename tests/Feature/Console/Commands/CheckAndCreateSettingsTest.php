<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Config;

use function Pest\Laravel\artisan;

it('checks for not existing portal settings', function () {
    artisan('app:check-and-create-settings')->expectsOutput('Starting with portal settings');
    Config::set('settings.portal.second_one', 'this is the second one');
    expect(count(Setting::portal()->data))->not()->toBe(count(config('settings.portal')));
    artisan('app:check-and-create-settings');
    expect(count(Setting::portal()->data))->toBe(count(config('settings.portal')));
});

it('checks for not existing opencast settings', function () {
    artisan('app:check-and-create-settings')->expectsOutput('Starting with opencast settings');
    Config::set('settings.opencast.second_one', 'this is the second one');
    expect(count(Setting::opencast()->data))->not()->toBe(count(config('settings.opencast')));
    artisan('app:check-and-create-settings');
    expect(count(Setting::opencast()->data))->toBe(count(config('settings.opencast')));
});
