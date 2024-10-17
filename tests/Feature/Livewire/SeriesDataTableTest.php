<?php

use App\Enums\Role;
use App\Livewire\SeriesDataTable;
use App\Models\Clip;
use App\Models\Semester;
use Facades\Tests\Setup\SeriesFactory;

use function Pest\Laravel\get;

uses()->group('backend');
beforeEach(function () {
    signInRole(Role::MODERATOR);
});

it('Backend index series page loads livewire component', function () {
    get(route('series.index'))->assertSeeLivewire('series-data-table');
});

it('shows series with clips and assets for moderators', function () {
    $series = SeriesFactory::withClips(1)->create();
    Livewire::test(SeriesDataTable::class)
        ->assertDontSee($series->title);

    $seriesWithAssets = SeriesFactory::withClips(1)->withAssets(1)->create();
    Livewire::test(SeriesDataTable::class)
        ->assertSee($seriesWithAssets->title);
});

it('shows series without clips or assets for admins', function () {
    auth()->logout();
    signInRole(Role::ADMIN);

    $seriesWithoutClips = SeriesFactory::create();
    Livewire::test(SeriesDataTable::class)
        ->assertSee($seriesWithoutClips->title);

    $seriesWithoutAssets = SeriesFactory::withClips(1)->create();
    Livewire::test(SeriesDataTable::class)
        ->assertSee($seriesWithoutAssets->title);
});

test('a moderator can see in series index all series that is member of', function () {
    $series = SeriesFactory::withClips(3)->withAssets(1)->create();

    Livewire::test(SeriesDataTable::class)
        ->assertSee($series->title);
});

it('filters series based on semester', function () {
    $seriesA = SeriesFactory::withClips(3)->withAssets(1)->create();
    $seriesA->clips->each(function (Clip $clip) {
        $clip->semester_id = Semester::find(1)->id;
        $clip->save();
    });
    $seriesB = SeriesFactory::withClips(3)->withAssets(1)->create();
    $seriesB->clips->each(function (Clip $clip) {
        $clip->semester_id = Semester::find(2)->id;
        $clip->save();
    });

    Livewire::test(SeriesDataTable::class)
        ->assertSee($seriesA->title)
        ->assertSee($seriesB->title)
        ->set('selectedSemesterID', Semester::find(1)->id)
        ->assertSee($seriesA->title)
        ->set('selectedSemesterID', Semester::find(2)->id)
        ->assertSee($seriesB->title);
});
