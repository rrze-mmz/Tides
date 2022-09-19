<?php

namespace Tests\Unit;

use App\Models\Series;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
    public function it_has_many_series(): void
    {
        $this->assertInstanceOf(HasMany::class, $this->user->series());
    }

    /** @test */
    public function it_has_many_clips(): void
    {
        $this->assertInstanceOf(HasMany::class, $this->user->clips());
    }

    /** @test */
    public function it_has_many_supervised_clips(): void
    {
        $this->assertInstanceOf(HasMany::class, $this->user->supervisedClips());
    }

    /** @test */
    public function it_fetch_all_user_series(): void
    {
        $this->assertInstanceOf(Builder::class, $this->user->getAllSeries());
    }

    /** @test */
    public function it_has_many_roles(): void
    {
        $this->assertInstanceOf(BelongsToMany::class, $this->user->roles());
    }

    /** @test */
    public function it_has_many_memberships(): void
    {
        $this->assertInstanceOf(BelongsToMany::class, $this->user->memberships());
    }

    /** @test */
    public function it_checks_whether_a_user_is_member_of_a_series(): void
    {
        $this->assertFalse($this->user->isMemberOf(Series::factory()->create()));
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
    public function it_check_for_superadmin_role(): void
    {
        $this->signInRole('superadmin');

        $this->assertTrue(auth()->user()->isSuperAdmin());
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
    public function it_check_for_assistant_role(): void
    {
        $this->signInRole('assistant');

        $this->assertTrue(auth()->user()->isAssistant());
    }

    /** @test */
    public function it_has_an_admins_scope(): void
    {
        $this->assertInstanceOf(Builder::class, User::admins());
    }
}
