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
        $this->user->assignRole('admin');

        $this->assertTrue($this->user->hasRole('admin'));

        $this->assertFalse($this->user->hasRole('user'));
    }

    /** @test */
    public function it_check_for_admin_role(): void
    {
        $this->signInRole('admin');

        $this->assertTrue(auth()->user()->isAdmin());
    }

    /** @test */
    public function it_check_for_moderator_role(): void
    {
        $this->signInRole('moderator');

        $this->assertTrue(auth()->user()->isModerator());
    }

    /** @test */
    public function it_can_return_user_full_name(): void
    {
        $this->assertEquals($this->user->getFullNameAttribute(), $this->user->first_name . ' ' . $this->user->last_name);
    }
}
