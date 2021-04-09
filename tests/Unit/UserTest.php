<?php


namespace Tests\Unit;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function a_user_has_series()
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(Collection::class, $user->series);
    }
    /** @test */
    public function a_user_has_clips()
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(Collection::class, $user->clips);
    }

    /** @test */
    public function it_has_many_roles()
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(BelongsToMany::class, $user->roles());
    }

    /** @test */
    public function it_can_assign_a_role()
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(User::class, $user->assignRole('admin'));

        $this->assertEquals('admin', $user->roles()->first()->name);

    }

    /** @test */
    public function it_can_check_for_a_role()
    {
        $user = User::factory()->create();

        $user->assignRole('admin')
            ->assignRole('tester');

        $this->assertTrue($user->hasRole('admin'));

        $this->assertTrue($user->hasRole('tester'));

        $this->assertFalse($user->hasRole('superadmin'));

        $this->assertEquals(2, $user->roles()->count());
    }

    /** @test */
    public function it_check_for_admin_role()
    {
        $this->signInAdmin();

        $this->assertTrue(auth()->user()->isAdmin());
    }
}
