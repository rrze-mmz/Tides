<?php


namespace Tests\Feature\Frontend;

use App\Models\Clip;
use App\Models\Organization;
use App\Models\Presenter;
use App\Models\Tag;
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

        $response = $this->get(route('api.clips') . '?query=test')->assertOk();

        $response->assertJson([
            ["id" => 1, "name" => $testClip->title]
        ]);

        $response = $this->get(route('api.clips') . '?query=clip')->assertOk();
        $response->assertJson([
            ["id" => 1, "name" => $testClip->title],
            ["id" => 2, "name" => $tidesClip->title]
        ]);
    }

    /** @test */
    public function it_search_tags(): void
    {
        Tag::factory()->create(['name' => 'algebra']);

        $response = $this->get(route('api.tags') . '?query=algebra');

        $response->assertJson([
            ["id" => 1, "name" => 'algebra']
        ]);
    }

    /** @test */
    public function it_search_presenters(): void
    {
        Presenter::factory()->create(['first_name' => 'John', 'last_name' => 'Doe']);

        $response = $this->get(route('api.presenters') . '?query=john');

        $response->assertJson([
            ["id" => 1, "name" => 'Dr. John Doe']
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

        $response = $this->get(route('api.organizations') . '?query=test');

        $response->assertJson([
            ["id" => 2, "name" => 'This is a test']
        ]);
    }
}
