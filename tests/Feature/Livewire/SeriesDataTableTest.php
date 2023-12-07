<?php

use App\Enums\Role;
use App\Livewire\SeriesDataTable;
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
