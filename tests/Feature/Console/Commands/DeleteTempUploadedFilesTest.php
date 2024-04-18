<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\travelTo;

it('deletes only temporary uploaded files older than 24 hours', function () {
    Storage::fake('local');

    $recentDirectory = 'tmp/recent';
    $oldDirectory = 'tmp/old';
    Storage::makeDirectory($recentDirectory);
    Storage::makeDirectory($oldDirectory);

    $recentFilePath = $recentDirectory.'/file.txt';
    $oldFilePath = $oldDirectory.'/file.txt';

    Storage::put($recentFilePath, 'Content');
    Storage::put($oldFilePath, 'Content');

    // Simulate the "last modified" time setting
    touch(Storage::path($recentFilePath), Carbon::now()->timestamp);
    touch(Storage::path($oldFilePath), Carbon::now()->timestamp);

    travelTo(Carbon::now()->addHours(22), function () use ($recentFilePath, $oldFilePath) {
        $this->artisan('app:delete-temp-uploaded-files');
        expect(Storage::exists($recentFilePath))->toBeTrue();
        expect(Storage::exists($oldFilePath))->toBeTrue();
    });

    travelTo(Carbon::now()->addHours(25), function () use ($recentFilePath, $oldFilePath) {
        $this->artisan('app:delete-temp-uploaded-files');
        expect(Storage::exists($recentFilePath))->toBeFalse();
        expect(Storage::exists($oldFilePath))->toBeFalse();
    });
});
