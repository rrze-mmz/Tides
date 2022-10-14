<?php

namespace Tests\Feature\Http\Controllers\Frontend;

use App\Models\Series;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserSubscriptionsControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_subscriptions_is_not_for_visitors_available(): void
    {
        $this->get(route('frontend.user.subscriptions'))->assertRedirect();
    }

    /** @test */
    public function it_shows_series_subscriptions_page_for_logged_in_user(): void
    {
        $this->signIn();

        $this->put(route('frontend.acceptUseTerms'), ['accept_use_terms' => 'on']);

        $this->get(route('frontend.user.subscriptions'))
            ->assertOk()
            ->assertViewIs('frontend.myPortal.subscriptions')
            ->assertSee('Your are subscribed to 0 Series');
    }

    /** @test */
    public function it_lists_user_subscriptions(): void
    {
        $this->signIn();

        $this->put(route('frontend.acceptUseTerms'), ['accept_use_terms' => 'on']);

        auth()->user()->subscriptions()->attach(Series::factory(3)->create());

        $this->get(route('frontend.user.subscriptions'))->assertSee(auth()->user()->subscriptions->first()->title);
    }
}
