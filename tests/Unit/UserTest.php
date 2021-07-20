<?php


namespace Tests\Unit;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase {
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function a_user_has_series(): void
    {
        $this->assertInstanceOf(Collection::class, $this->user->series);
    }

    /** @test */
    public function a_user_has_clips(): void
    {
        $this->assertInstanceOf(Collection::class, $this->user->clips);
    }

    /** @test */
    public function it_has_many_roles(): void
    {
        $this->assertInstanceOf(BelongsToMany::class, $this->user->roles());
    }

    /** @test */
    public function it_can_assign_a_role(): void
    {
        $this->assertInstanceOf(User::class, $this->user->assignRole('admin'));

        $this->assertEquals('admin', $this->user->roles()->first()->name);
    }

    /** @test */
    public function it_can_check_for_a_role(): void
    {
        $this->user->assignRole('admin')
            ->assignRole('tester');

        $this->assertTrue($this->user->hasRole('admin'));

        $this->assertTrue($this->user->hasRole('tester'));

        $this->assertFalse($this->user->hasRole('superadmin'));

        $this->assertEquals(2, $this->user->roles()->count());
    }

    /** @test */
    public function it_check_for_admin_role(): void
    {
        $this->signInAdmin();

        $this->assertTrue(auth()->user()->isAdmin());
    }
}
