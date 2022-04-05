<?php

namespace Tests\Feature\Backend;

use App\Http\Livewire\PresenterDataTable;
use App\Models\Clip;
use App\Models\Presenter;
use App\Models\Series;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class ManagePresenters extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->signInRole('admin');
    }

    /** @test */
    public function a_moderator_is_not_allowed_to_manage_presenters(): void
    {
        auth()->logout();

        $this->signInRole('moderator');

        $presenter = Presenter::factory()->create();

        $this->get(route('presenters.index'))->assertForbidden();
        $this->get(route('presenters.create'))->assertForbidden();
        $this->post(route('presenters.store'), [])->assertForbidden();
        $this->delete(route('presenters.destroy', $presenter))->assertForbidden();
    }

    /** @test */
    public function an_assistant_is_allowed_to_manage_presenters(): void
    {
        auth()->logout();

        $this->signInRole('assistant');

        $presenter = Presenter::factory()->create();

        $this->get(route('presenters.index'))->assertOk();
        $this->get(route('presenters.create'))->assertOk();
        $this->post(route('presenters.store'), [])->assertRedirect(route('presenters.create'));
        $this->delete(route('presenters.destroy', $presenter))->assertRedirect(route('presenters.index'));
    }

    /** @test */
    public function it_renders_a_datatable_for_presenters_for_users_with_assistant_role(): void
    {
        auth()->logout();

        $this->signInRole('assistant');

        $this->get(route('presenters.index'))->assertOk();
    }

    /** @test */
    public function it_renders_a_datatable_for_presenters_for_users_with_admin_role(): void
    {
        $this->get(route('presenters.index'))->assertOk();
    }

    /** @test */
    public function it_renders_a_datatable_for_presenters_for_users_with_superadmin_role(): void
    {
        auth()->logout();

        $this->signInRole('superadmin');

        $this->get(route('presenters.index'))->assertOk();
    }

    /** @test */
    public function it_contains_presenter_data_table_livewire_component_on_index_page(): void
    {
        $this->get(route('presenters.index'))->assertSeeLivewire('presenter-data-table');
    }

    /** @test */
    public function it_can_search_for_presenter_name_in_index_presenters_data_table(): void
    {
        $bob = Presenter::factory()->create(['first_name' => 'Bob', 'last_name' => 'Tester']);
        $alice = Presenter::factory()->create(['first_name' => 'Alice', 'last_name' => 'Tester']);

        Livewire::test(PresenterDataTable::class)
            ->set('search', 'bob')
            ->assertSee($bob->username)
            ->assertDontSee($alice->username);
    }

    /** @test */
    public function it_can_search_for_a_presenter_email_in_index_presenters_data_table(): void
    {
        $bob = Presenter::factory()->create(['email' => 'bob@example.org']);
        $alice = Presenter::factory()->create(['email' => 'alice@example.org']);

        Livewire::test(PresenterDataTable::class)
            ->set('search', 'bob@example.org')
            ->assertSee($bob->username)
            ->assertDontSee($alice->username);
    }

    /** @test */
    public function it_can_sorts_by_user_name_ascending_in_index_presenters_data_table(): void
    {
        $bob = Presenter::factory()->create([
            'first_name' => 'Bob',
            'last_name'  => 'Tester',
            'username'   => 'bob01'
        ]);
        $alice = Presenter::factory()->create([
            'first_name' => 'Alice',
            'last_name'  => 'Tester',
            'username'   => 'alice01'
        ]);
        $gregor = Presenter::factory()->create([
            'first_name' => 'Gregor',
            'last_name'  => 'Tester',
            'username'   => 'gregor01'
        ]);

        Livewire::test(PresenterDataTable::class)
            ->call('sortBy', 'username')
            ->assertSeeInOrder([$alice->username, $bob->username, $gregor->username]);
    }

    /** @test */
    public function it_can_sorts_by_user_name_descending_in_index_presenters_data_table(): void
    {
        $bob = Presenter::factory()->create([
            'first_name' => 'Bob',
            'last_name'  => 'Tester',
            'username'   => 'bob01'
        ]);
        $alice = Presenter::factory()->create([
            'first_name' => 'Alice',
            'last_name'  => 'Tester',
            'username'   => 'alice01'
        ]);
        $gregor = Presenter::factory()->create([
            'first_name' => 'Gregor',
            'last_name'  => 'Tester',
            'username'   => 'gregor01'
        ]);

        Livewire::test(PresenterDataTable::class)
            ->call('sortBy', 'username')
            ->call('sortBy', 'username')
            ->assertSeeInOrder([$gregor->username, $bob->username, $alice->username]);
    }

    /** @test */
    public function it_can_sorts_by_user_email_ascending_in_index_presenters_data_table(): void
    {
        $bob = Presenter::factory()->create(['email' => 'bob@example.org']);
        $alice = Presenter::factory()->create(['email' => 'alice@example.org']);
        $gregor = Presenter::factory()->create(['email' => 'gregor@example.org']);

        Livewire::test(PresenterDataTable::class)
            ->call('sortBy', 'email')
            ->assertSeeInOrder([$alice->username, $bob->username, $gregor->username]);
    }

    /** @test */
    public function it_can_sorts_by_user_email_descending_in_index_presenters_data_table(): void
    {
        $bob = Presenter::factory()->create(['email' => 'bob@example.org']);
        $alice = Presenter::factory()->create(['email' => 'alice@example.org']);
        $gregor = Presenter::factory()->create(['email' => 'gregor@example.org']);

        Livewire::test(PresenterDataTable::class)
            ->call('sortBy', 'email')
            ->call('sortBy', 'email')
            ->assertSeeInOrder([$gregor->username, $bob->username, $alice->username]);
    }

    /** @test */
    public function an_assistant_can_view_add_presenter_form(): void
    {
        auth()->logout();

        $this->signInRole('assistant');
        
        $this->get(route('presenters.create'))->assertOk();
    }

    /** @test */
    public function an_admin_can_view_add_presenter_form(): void
    {
        $this->get(route('presenters.create'))->assertOk();
    }

    /** @test */
    public function it_requires_a_first_name_and_a_last_name_for_a_new_presenter(): void
    {
        $attributes = Presenter::factory()->raw(['first_name' => '', 'last_name' => '']);

        $this->post(route('presenters.store', $attributes))
            ->assertSessionHasErrors('first_name')->assertSessionHasErrors('last_name');
    }

    /** @test */
    public function it_requires_a_unique_username_for_a_new_presenter(): void
    {
        $presenter = Presenter::factory()->create();

        $attributes = Presenter::factory()->raw(['username' => $presenter->username]);

        $this->post(route('presenters.store', $attributes))->assertSessionHasErrors('username');
    }

    /** @test */
    public function it_requires_a_unique_email_for_a_new_presenter(): void
    {
        $presenter = Presenter::factory()->create();

        $attributes = Presenter::factory()->raw(['email' => $presenter->email]);

        $this->post(route('presenters.store', $attributes))->assertSessionHasErrors('email');
    }

    /** @test */
    public function create_presenter_form_should_remember_old_values_on_validation_error()
    {
        $attributes = [
            'degree_title' => 'Dr.',
            'first_name'   => 'John',
            'last_name'    => '',
            'username'     => 'johndoe13',
            'email'        => ''
        ];

        $this->post(route('presenters.store'), $attributes)->assertSessionHasErrors(['last_name']);

        $this->followingRedirects();

        $this->get(route('presenters.create'))->assertSee($attributes);
    }

    /** @test */
    public function an_admin_can_create_a_new_presenter(): void
    {
        $attributes = [
            'degree_title' => 'Dr. Ing-',
            'first_name'   => $this->faker->firstNameFemale(),
            'last_name'    => $this->faker->lastName(),
            'username'     => 'johndoe13',
            'email'        => 'john.doe@test.com',
        ];

        $this->post(route('presenters.store'), $attributes);

        $this->assertDatabaseHas('presenters', ['username' => $attributes['username']]);
    }

    /** @test */
    public function a_moderator_is_not_allowed_to_view_presenter_edit_form(): void
    {
        auth()->logout();

        $this->get(route('presenters.edit', $presenter = Presenter::factory()->create()))
            ->assertRedirect(route('login'));

        $this->signInRole('moderator');

        $this->get(route('presenters.edit', $presenter))->assertStatus(403);
    }

    /** @test */
    public function admin_user_can_view_edit_presenter_form(): void
    {
        $presenter = Presenter::factory()->create();

        $this->get(route('presenters.edit', $presenter))
            ->assertOk()
            ->assertSee($presenter->degree_title)
            ->assertSee($presenter->first_name)
            ->assertSee($presenter->last_name)
            ->assertSee($presenter->username)
            ->assertSee($presenter->email);
    }

    /** @test */
    public function it_shows_all_presenters_series_on_edit_page(): void
    {
        $presenter = Presenter::factory()->create();
        $series = Series::factory()->create();

        $series->addPresenters($presenter->get());

        //flush session data to remove the update clip model message
        session()->flush();

        $this->get(route('presenters.edit', $presenter))->assertSee(Str::limit($series->title, 20, '...'));
    }

    /** @test */
    public function it_shows_all_presenters_clips_on_edit_page(): void
    {
        $presenter = Presenter::factory()->create();
        $clip = Clip::factory()->create();

        $clip->addPresenters($presenter->get());

        //flush session data to remove the update clip model message
        session()->flush();

        $this->get(route('presenters.edit', $presenter))->assertSee(Str::limit($clip->title, 20, '...'));
    }

    /** @test */
    public function an_admin_can_update_presenter_information(): void
    {
        $presenter = Presenter::factory()->create();

        $this->patch(route('presenters.update', $presenter), [
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'username'   => 'johndoe13',
            'email'      => 'john.doe@test.com'
        ]);

        $presenter->refresh();

        $this->assertDatabaseHas('presenters', ['first_name' => 'John']);
    }

    /** @test */
    public function it_should_display_an_error_if_a_presenter_with_the_same_username_exists(): void
    {
        $john = Presenter::factory()->create();
        $alice = Presenter::factory()->create();

        $this->patch(route('presenters.update', $alice), [
            'first_name' => $alice->first_name,
            'last_name'  => $alice->last_name,
            'username'   => $john->username,
            'email'      => $alice->email,
        ])->assertSessionHasErrors(['username']);

        $alice->refresh();

        $this->assertNotEquals($john->username, $alice->usernmae);
    }

    /** @test */
    public function it_should_display_an_error_if_a_presenter_with_the_same_email_exists(): void
    {
        $john = Presenter::factory()->create();
        $alice = Presenter::factory()->create();

        $this->patch(route('presenters.update', $alice), [
            'first_name' => $alice->first_name,
            'last_name'  => $alice->last_name,
            'username'   => $alice->username,
            'email'      => $john->email,
        ])->assertSessionHasErrors(['email']);

        $alice->refresh();

        $this->assertNotEquals($john->email, $alice->email);
    }

    /** @test */
    public function a_moderator_is_not_allowed_to_delete_a_presenter(): void
    {
        $presenter = Presenter::factory()->create();

        auth()->logout();

        $this->delete(route('presenters.destroy', $presenter))->assertRedirect(route('login'));

        $this->signInRole('moderator');

        $this->delete(route('presenters.destroy', $presenter))->assertStatus(403);
    }

    /** @test */
    public function an_admin_can_delete_a_presenter(): void
    {
        $presenter = Presenter::factory()->create();

        $this->delete(route('presenters.destroy', $presenter));

        $this->assertDatabaseMissing('presenters', ['id' => $presenter->id]);
    }
}
