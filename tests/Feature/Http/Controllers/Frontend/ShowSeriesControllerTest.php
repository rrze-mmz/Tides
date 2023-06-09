<?php

use App\Enums\Acl;
use App\Enums\Role;
use App\Models\Asset;
use App\Models\Clip;
use App\Models\Series;
use Facades\Tests\Setup\SeriesFactory;
use function Pest\Laravel\get;

it('shows all available series on series index page', function () {
    $series = SeriesFactory::withClips(2)->withAssets(1)->create();
    get(route('frontend.series.index'))->assertSee($series->title);
});

test(' series route working also with course urls keeping backwards compatibility', function () {
    $series = Series::factory()->create();

    $this->get('/course/id/'.$series->id)->assertRedirectToRoute('frontend.series.show', $series);
});

it('lists all clips with media assets to visitors', function () {
    $series = SeriesFactory::withClips(2)->withAssets(2)->create();
    $clipWithoutAsset = Clip::factory()->create(['series_id' => $series->id]);

    $this->get(route('frontend.series.show', $series))
        ->assertSee($series->first()->title)
        ->assertDontSee($clipWithoutAsset->title);
});

it('shows a series public page even without clips to series owner', function () {
    $series = SeriesFactory::ownedBy(signInRole(Role::MODERATOR))->create();
    get(route('frontend.series.show', $series))->assertOk();
});

it('shows a forbidden series page for series without clips to visitors', function () {
    get(route('frontend.series.show', SeriesFactory::create()))->assertForbidden();
});

it('shows an unauthorized page for non public series to visitors', function () {
    $this->get(route('frontend.series.show', SeriesFactory::withClips(2)->withAssets(1)->notPublic()->create()))
        ->assertForbidden();
});

it('shows series public page if series is not public to series owner', function () {
    $this->get(route(
        'frontend.series.show',
        SeriesFactory::ownedBy($this->signIn())->withClips(2)->withAssets(1)->notPublic()->create()
    ))
        ->assertOk();
});

it('shows series public page to portal admins', function () {
    signInRole(Role::ADMIN);
    $this->get(route('frontend.series.show', SeriesFactory::withClips(2)->withAssets(1)->notPublic()->create()))
        ->assertOk();
});

test('series public page lists all public clips', function () {
    $series = SeriesFactory::withClips(2)->withAssets(1)->create();
    $firstClip = Clip::find(1);
    $secondClip = Clip::find(2);
    $firstClip->is_public = false;
    $firstClip->save();

    get(route('frontend.series.show', $series))
        ->assertSee($secondClip->title)
        ->assertDontSee($firstClip->title);
});

test('series public page lists all clips acls', function () {
    $series = SeriesFactory::withClips(2)->withAssets(1)->create();
    $series->clips->each(function ($clip) {
        $clip->addAcls(collect([Acl::PORTAL(), Acl::PASSWORD()]));
    });

    get(route('frontend.series.show', $series))
        ->assertSee(Acl::PORTAL->lower().','.Acl::PASSWORD->lower());
});

it('shows for each clip if it is locked or not', function () {
    $series = SeriesFactory::withClips(2)->withAssets(1)->create();
    $series->clips->each(function ($clip) {
        $clip->addAcls(collect([Acl::PORTAL()]));
    });

    get(route('frontend.series.show', $series))->assertSee('Lock clip');

    signIn();

    get(route('frontend.series.show', $series))->assertSee('Unlock clip');
});

it('list all clips with semester info', function () {
    $series = SeriesFactory::withClips(2)->withAssets(1)->create();

    get(route('frontend.series.show', $series))->assertSee($series->clips->first()->semester->name);
});

it('shows series semester info', function () {
    get(route('frontend.series.show', $series = SeriesFactory::withClips(2)->withAssets(1)->create()))
        ->assertSee($series->latestClip->semester->name);
});

it('has a series feed button', function () {
    get(route('frontend.series.show', SeriesFactory::withClips(2)->withAssets(1)->create()))
        ->assertSee('Feeds');
});

it('shows feed links for different formats', function () {
    $series = SeriesFactory::withClips(2)->withAssets(2)->create();
    $series->clips->first()->assets()->save(Asset::factory()->create(['width' => 640]));

    get(route('frontend.series.show', $series))
        ->assertSee('QHD')
        ->assertSee('SD');
});

it('shows series mutliple semester info if has clips from multiple semesters', function () {
    $series = SeriesFactory::withClips(2)->withAssets(1)->create();
    $firstClip = $series->clips()->first();
    $firstClip->semester_id = 3;
    $firstClip->save();

    get(route('frontend.series.show', $series))
        ->assertSee($series->clips()->first()->semester->name.', '.$series->latestClip->semester->name);
});

it('shows a subscribe button for logged in users', function () {
    $series = SeriesFactory::withClips(2)->withAssets(1)->create();
    get(route('frontend.series.show', $series))->assertDontSee('Subscribe');
    signin();

    get(route('frontend.series.show', $series))->assertSee('Subscribe');
});

it('shows an unlock button if series has password protection', function () {
    $series = SeriesFactory::withClips(2)->withAssets(1)->create();
    $series->clips->each(function ($clip) {
        $clip->addAcls(collect([Acl::PASSWORD()]));
    });

    $this->get(route('frontend.series.show', $series))->assertSee('Unlock series');
});

it('hides unlock button to portal admins', function () {
    $series = SeriesFactory::withClips(2)->withAssets(1)->create();
    $series->clips->each(function ($clip) {
        $clip->addAcls(collect([Acl::PASSWORD()]));
    });

    signInRole(Role::ADMIN);

    get(route('frontend.series.show', $series))->assertDontSee('Unlock series');
});
