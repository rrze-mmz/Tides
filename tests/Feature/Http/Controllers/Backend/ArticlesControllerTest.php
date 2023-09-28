<?php

use App\Enums\Role;
use App\Models\Article;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\patch;
use function Pest\Laravel\post;

uses()->group('backend');

it('denies access to articles routes for non logged in users', function () {
    get(route('articles.index'))->assertRedirect('login');
    $article = Article::factory()->create();
    signIn();

    get(route('articles.index'))->assertForbidden();
    get(route('articles.create'))->assertForbidden();
    post(route('articles.store'))->assertForbidden();
    get(route('articles.edit', $article))->assertForbidden();
    patch(route('articles.update', $article))->assertForbidden();
    delete(route('articles.destroy', $article))->assertForbidden();
});

it('denies access to articles routes for moderators', function () {
    $article = Article::factory()->create();
    signInRole(Role::MODERATOR);

    get(route('articles.index'))->assertForbidden();
    get(route('articles.create'))->assertForbidden();
    post(route('articles.store'))->assertForbidden();
    get(route('articles.edit', $article))->assertForbidden();
    patch(route('articles.update', $article))->assertForbidden();
    delete(route('articles.destroy', $article))->assertForbidden();
});

it('denies access to articles routes for assistants', function () {
    $article = Article::factory()->create();
    signInRole(Role::ASSISTANT);

    get(route('articles.index'))->assertForbidden();
    get(route('articles.create'))->assertForbidden();
    post(route('articles.store'))->assertForbidden();
    get(route('articles.edit', $article))->assertForbidden();
    patch(route('articles.update', $article))->assertForbidden();
    delete(route('articles.destroy', $article))->assertForbidden();
});

it('allows access to articles routes for admin and superadmin', function () {
    $article = Article::factory()->create();
    signInRole(Role::ADMIN);

    get(route('articles.index'))->assertOk();
    get(route('articles.create'))->assertOk();
    post(route('articles.store'))->assertOk();
    get(route('articles.edit', $article))->assertOk();
    patch(route('articles.update', $article))->assertRedirect();
    delete(route('articles.destroy', $article))->assertOk();
});

it('shows all articles in the index page', function () {
    signInRole(Role::ADMIN);
    [$articleA, $articleB] = Article::factory(2)->create();

    get(route('articles.index'))->assertSee($articleA->slug)->assertSee($articleB->slug);
});

it('displays edit article form and all form fields ', function () {
    $article = Article::factory()->create();
    signInRole(Role::ADMIN);

    get(route('articles.edit', $article))
        ->assertSee('title_en')
        ->assertSee('title_de')
        ->assertSee('content_en')
        ->assertSee('content_de')
        ->assertSee('is_published')
        ->assertViewIs('backend.articles.edit');
});

it('updates an article', function () {
    $article = Article::factory()->create();
    signInRole(Role::ADMIN);
    patch(route('articles.update', $article), $attributes = [
        'title_en' => 'Test title',
        'title_de' => 'Das ist ein Test',
        'content_en' => 'That is a test text',
        'content_de' => 'Das ist ein Test Text',
    ]);

    $article->refresh();

    assertDatabaseHas('articles', $attributes);
});
