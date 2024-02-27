<?php

use Illuminate\Support\Carbon;

use function Pest\Laravel\travelTo;

it('deletes only temporary uploaded files older than 24 hours', function () {
    Storage::fake('local');

    // Create directories for testing
    $recentDirectory = 'tmp/recent';
    $oldDirectory = 'tmp/old';
    Storage::makeDirectory($recentDirectory);
    Storage::makeDirectory($oldDirectory);

    // Place a file in each directory to simulate their "last modified" time
    $recentFilePath = $recentDirectory.'/file.txt';
    $oldFilePath = $oldDirectory.'/file.txt';
    Storage::put($recentFilePath, 'Content');
    Storage::put($oldFilePath, 'Content');

    // Manually set the timestamps to simulate age
    touch(Storage::path($recentFilePath), Carbon::now()->subHours(23)->getTimestamp());
    touch(Storage::path($oldFilePath), Carbon::now()->subHours(25)->getTimestamp());

    travelTo(Carbon::now()->addHours(23), function () use ($recentDirectory) {
        $this->artisan('app:delete-temp-uploaded-files');
        expect(Storage::exists($recentDirectory))->toBeTrue();
    });
    travelTo(Carbon::now()->addHours(25), function () use ($recentDirectory) {
        $this->artisan('app:delete-temp-uploaded-files');
        expect(Storage::exists($recentDirectory))->toBeFalse();
    });
});
