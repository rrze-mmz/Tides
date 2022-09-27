<?php

namespace Tests\Unit;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_an_opencast_scope(): void
    {
        Setting::factory()->create(['name' => 'opencast']);

        $this->assertInstanceOf(Setting::class, Setting::opencast());
    }

    /** @test */
    public function it_has_a_portal_scope(): void
    {
        Setting::factory()->create(['name' => 'portal']);

        $this->assertInstanceOf(Setting::class, Setting::portal());
    }

    /** @test */
    public function it_has_a_streaming_scope(): void
    {
        Setting::factory()->create(['name' => 'streaming']);

        $this->assertInstanceOf(Setting::class, Setting::streaming());
    }
}
