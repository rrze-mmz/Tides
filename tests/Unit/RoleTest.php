<?php


namespace Tests\Unit;

use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function it_has_many_users(): void
    {
        $role = Role::factory()->create(['name' => 'admin']);

        $this->assertInstanceOf(BelongsToMany::class, $role->users());
    }
}
