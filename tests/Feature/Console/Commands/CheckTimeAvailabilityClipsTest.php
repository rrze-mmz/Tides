<?php

use App\Models\Clip;
use Facades\Tests\Setup\ClipFactory;
use Illuminate\Support\Carbon;

use function Pest\Laravel\artisan;
use function Pest\Laravel\travelTo;

uses()->group('backend');

it('outputs a message and skip checks if no time availability clips found', function () {
    Clip::factory(10)->create();

    artisan('app:check-time-availability-clips')->expectsOutput('No time availability Clips found for '.Carbon::now());
});

it('outputs all available time available clips at the time of command running', function () {
    //    Clip::factory(10)->create();

    Clip::factory()->create([
        'has_time_availability' => true,
        'time_availability_start' => Carbon::now()->subDays(2),
        'time_availability_end' => Carbon::now()->addDays(4),
    ]);
    Clip::factory()->create([
        'has_time_availability' => true,
        'time_availability_start' => Carbon::now()->subHours(2),
        'time_availability_end' => Carbon::now()->addHours(4),
    ]);
    Clip::factory()->create([
        'has_time_availability' => true,
        'time_availability_start' => Carbon::now()->subDays(4),
        'time_availability_end' => Carbon::now()->subDays(2),
    ]);

    artisan('app:check-time-availability-clips')->expectsOutput('Found 3 clips with time availability');
});

it('publish a clip if commands current time is equal or after time availability start', function () {
    $clip = ClipFactory::create(Clip::factory()->raw([
        'is_public' => false,
        'has_time_availability' => true,
        'time_availability_start' => Carbon::now()->subMinutes(5),
        'time_availability_end' => Carbon::now()->addHours(12),
    ]));
    artisan('app:check-time-availability-clips');

    $clip->refresh();

    expect($clip->is_public)->toBe(1);
});

it('retracts a clip if commands current time is equal or after time availability end time', function () {
    $clip = Clip::factory()->create([
        'is_public' => true,
        'has_time_availability' => true,
        'time_availability_start' => Carbon::now(),
        'time_availability_end' => Carbon::now()->addHours(12),
    ]);

    travelTo($clip->time_availability_start->addHour(1), function () use ($clip) {
        artisan('app:check-time-availability-clips')
            ->expectsOutput("ClipID: {$clip->id} / Title:{$clip->episode} {$clip->title} is still available for users");
        $clip->refresh;
        expect($clip->is_public)->toBe(1);
    });

    travelTo($clip->time_availability_end, function () use ($clip) {
        artisan('app:check-time-availability-clips')
            ->expectsOutput("ClipID: {$clip->id} / Title:{$clip->episode} {$clip->title} will be withdrawn for users");
        $clip->refresh();
        expect($clip->is_public)->toBe(0);
    });
});

it('will disable time availability for clips with end date of null', function () {
    $clip = Clip::factory()->create([
        'is_public' => true,
        'has_time_availability' => true,
        'time_availability_start' => Carbon::now()->subDays(10),
        'time_availability_end' => null,
    ]);

    artisan('app:check-time-availability-clips')
        ->expectsOutput("ClipID: {$clip->id} / Title:{$clip->episode} $clip->title will be available for users
                        and time availability will be turned off");
    $clip->refresh();

    expect($clip->has_time_availability)->toBe(0);
});

it('will do nothing if start date is in the future', function () {
    $clip = Clip::factory()->create([
        'is_public' => false,
        'has_time_availability' => true,
        'time_availability_start' => Carbon::now()->addDay(),
        'time_availability_end' => null,
    ]);

    artisan('app:check-time-availability-clips')
        ->expectsOutput("ClipID: {$clip->id} / Title:{$clip->episode} {$clip->title} should remain offline");
});
