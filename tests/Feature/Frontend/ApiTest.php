<?php


namespace Tests\Feature\Frontend;

use App\Models\Clip;
use App\Models\Organization;
use App\Models\Presenter;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_search_clips(): void
    {
        $testClip = Clip::factory()->create(['title' => 'test clip']);

        $tidesClip = Clip::factory()->create(['title' => 'tides clip']);

        $this->get(route('api.clips') . '?query=test')
            ->assertOk()
            ->assertJson([
                ["id" => 1, "name" => $testClip->title]
            ]);

        $this->get(route('api.clips') . '?query=clip')
            ->assertOk()
            ->assertJson([
                ["id" => 1, "name" => $testClip->title],
                ["id" => 2, "name" => $tidesClip->title]
            ]);
    }

    /** @test */
    public function it_search_tags(): void
    {
        Tag::factory()->create(['name' => 'algebra']);

        $this->get(route('api.tags') . '?query=algebra')
            ->assertOk()
            ->assertJson([
                ["id" => 1, "name" => 'algebra']
            ]);
    }

    /** @test */
    public function it_search_presenters(): void
    {
        Presenter::factory()->create(['first_name' => 'John', 'last_name' => 'Doe']);

        $this->get(route('api.presenters') . '?query=john')
            ->assertOk()
            ->assertJson([
                ["id" => 1, "name" => 'Dr. John Doe']
            ]);
    }

    /** @test */
    public function it_is_not_allowed_for_guest_or_simple_users_to_use_user_api(): void
    {
        $this->get(route('api.users') . '?query=john')->assertForbidden();
    }

    /** @test */
    public function it_search_users_only_for_admin_and_moderator_roles(): void
    {
        $john = User::factory()->create(['first_name' => 'John', 'last_name' => 'Doe', 'username' => 'test123']);
        $jane = User::factory()->create(['first_name' => 'Jane', 'last_name' => 'Drake']);
        $john->assignRole('moderator');
        $jane->assignRole('moderator');

        $this->signInRole('moderator');

        $response = $this->get(route('api.users') . '?query=john')->assertOk();

        $response->assertJson([
            [
                'id'   => $john->id,
                'name' => 'John Doe/tes****',
            ]
        ]);
    }

    /** @test */
    public function it_search_organizations(): void
    {
        Organization::factory()->create([
            'org_id'             => 2,
            'name'               => 'This is a test',
            'parent_org_id'      => 2,
            'orgno'              => '0000000001',
            'shortname'          => 'Main organization unit',
            'staff'              => null,
            'startdate'          => now(),
            'operationstartdate' => now(),
            'operationenddate'   => '2999-12-31',
            'created_at'         => now(),
            'updated_at'         => null,
        ]);

        $this->get(route('api.organizations') . '?query=test')
            ->assertOk()
            ->assertJson([
                ["id" => 2, "name" => 'This is a test']
            ]);
    }
}
