<?php

use App\Enums\Role;
use App\Livewire\ArticlesDataTable;
use App\Models\Article;

use function Pest\Laravel\get;

uses()->group('backend');

beforeEach(function () {
    signInRole(Role::ADMIN);
});

it('loads an articles livewire component on articles index page', function () {
    get(route('articles.index'))->assertSeeLivewire('articles-data-table');
});

it('can search for articles on articles index page', function () {
    signInRole(Role::ADMIN);
    [$articleA, $articleB] = Article::factory(2)->create();

    Livewire::test(ArticlesDataTable::class)
        ->assertSee($articleA->title_en)
        ->assertSee($articleB->title_en)
        ->set('search', $articleA->title_de)
        ->assertSee($articleA->title_en)
        ->assertDontSee($articleB->title_en);
});

it('can sort an article by his title', function () {
    $articleA = Article::factory()->create(['title_en' => 'Alpha title']);
    $articleB = Article::factory()->create(['title_en' => 'Beta title']);

    Livewire::test(ArticlesDataTable::class)
        ->call('sortBy', 'title_en')
        ->assertSeeInOrder([$articleA->title_en, $articleB->title_en]);
});
