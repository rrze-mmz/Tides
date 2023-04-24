<?php

namespace Tests\Feature\Http\Controllers\Frontend;

use App\Enums\Acl;
use App\Enums\Role;
use App\Models\Clip;
use App\Models\Series;
use Facades\Tests\Setup\ClipFactory;
use Facades\Tests\Setup\SeriesFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function should_show_project_name(): void
    {
        $this->get(route('home'))->assertSee(env('APP_NAME'));
    }

    /** @test */
    public function it_has_a_language_switcher(): void
    {
        $this->get(route('home'))->assertSee('EN')->assertSee('DE');
    }

    /** @test */
    public function it_has_a_series_top_menu_item(): void
    {
        $menuItem = '<a href="'.route('frontend.series.index').'" class="text-white text-lg">';

        $this->get(route('home'))->assertSee($menuItem, false);
    }

    /** @test */
    public function it_has_a_clips_top_menu_item(): void
    {
        $menuItem = '<a href="'.route('frontend.clips.index').'" class="text-white text-lg">';

        $this->get(route('home'))->assertSee($menuItem, false);
    }

    /** @test */
    public function it_has_a_faculties_top_menu_item(): void
    {
        $menuItem = '<a href="'.route('frontend.organizations.index').'" class="text-white text-lg">';

        $this->get(route('home'))->assertSee($menuItem, false);
    }

    /** @test */
    public function it_changes_portal_language(): void
    {
        $this->followingRedirects()->get('/set_lang/de');

        $this->get(route('home'))->assertSee('Letzte Videoaufnahmen');
    }

    /** @test */
    public function it_does_not_display_series_with_clips_without_assets(): void
    {
        $series = SeriesFactory::withClips(1)->create();

        $this->get(route('home'))->assertDontSee($series->title);
    }

    /** @test */
    public function it_displays_latest_series_with_clips_that_have_assets(): void
    {
        $series = SeriesFactory::create();

        $clip = ClipFactory::withAssets(1)->create();

        $series->clips()->save($clip);

        $this->get(route('home'))->assertSee(Str::limit($series->title, 20, ''));
    }

    /** @test */
    public function it_should_not_display_series_that_is_not_public(): void
    {
        $series = SeriesFactory::create();

        $clip = ClipFactory::withAssets(1)->create();

        $series->clips()->save($clip);

        $this->get(route('home'))->assertSee(Str::limit($series->title, 20, ''));

        $series->is_public = false;

        $series->save();

        $this->get(route('home'))->assertDontSee(Str::limit($series->title, 20, ''));
    }

    /** @test */
    public function it_does_not_display_clips_without_assets(): void
    {
        $clip = ClipFactory::create();

        $this->get(route('home'))->assertDontSee(Str::limit($clip->title, 20, '...'));
    }

    /** @test */
    public function it_does_not_display_clips_that_belong_to_a_series(): void
    {
        $series = SeriesFactory::withClips(1)->create();

        $this->get(route('home'))->assertDontSee(Str::limit($series->clips()->first()->title, 20, '...'));
    }

    /** @test */
    public function it_displays_clips_with_video_assets(): void
    {
        $clip = ClipFactory::withAssets(1)->create();

        $this->get(route('home'))->assertSee(Str::limit($clip->title, 20, '...'));
    }

    /** @test */
    public function it_should_not_display_clips_that_are_not_public(): void
    {
        $clip = ClipFactory::withAssets(1)->create();

        $this->get(route('home'))->assertSee(Str::limit($clip->title, 20, '...'));

        $clip->is_public = false;

        $clip->save();

        $this->get(route('home'))->assertDontSee(Str::limit($clip->title, 20, '...'));
    }

    /** @test */
    public function it_shows_dashboard_menu_item_for_admins(): void
    {
        $this->signInRole(Role::ADMIN);

        $this->get(route('home'))->assertSee('Dashboard');
    }

    /** @test */
    public function it_shows_dashboard_menu_item_for_moderators(): void
    {
        $this->signInRole(Role::MODERATOR);

        $this->get(route('home'))->assertSee('Dashboard');
    }

    /** @test */
    public function it_shows_dashboard_menu_item_for_assistants(): void
    {
        $this->signInRole(Role::ASSISTANT);

        $this->get(route('home'))->assertSee('Dashboard');
    }

    /** @test */
    public function it_show_an_hide_logged_in_user_series_subscriptions(): void
    {
        $this->signIn();

        $userSettings = auth()->user()->settings();

        SeriesFactory::withClips(2)->withAssets(2)->create(10);

        $this->assertDatabaseHas('settings', [
            'name' => auth()->user()->username,
            'data' => json_encode(config('settings.user')), ]);

        $this->acceptUseTerms();

        $userSettings->refresh();

        $this->get(route('home'))->assertDontSee('Your Series subscriptions');

        $attributes = [
            'language' => 'en',
            'show_subscriptions_to_home_page' => 'on',
        ];

        $this->put(route('frontend.userSettings.update'), $attributes);

        $this->get(route('home'))
            ->assertSee('Your Series subscriptions')
            ->assertSee(' You are not subscribed to any series');

        auth()->user()->subscriptions()->attach([
            Series::find(3)->id, Series::find(4)->id,
        ]);

        auth()->user()->refresh();

        $this->get(route('home'))
            ->assertSee('Your Series subscriptions')
            ->assertDontSee(' You are not subscribed to any series');
    }

    /** @test */
    public function it_hide_non_visible_clip_acls_in_series_description(): void
    {
        $series = SeriesFactory::withClips(2)->withAssets(1)->create();

        $firstClip = Clip::find(1);
        $secondClip = Clip::find(2);

        $firstClip->addAcls(collect([Acl::PORTAL()]));
        $secondClip->addAcls(collect([Acl::PASSWORD()]));

        $this->get(route('home'))->assertSee('portal, password');

        //clip has no assets thus should not be displayed to visitors
        $thirdClip = Clip::factory()->create(['series_id' => $series]);

        $thirdClip->addAcls(collect([Acl::LMS()]));

        $this->get(route('home'))->assertDontSee('portal, password, lms');
    }

    /*
 * Helper functions
 *
 */
    private function acceptUseTerms()
    {
        $this->put(route('frontend.acceptUseTerms'), ['accept_use_terms' => 'on']);
    }
}
