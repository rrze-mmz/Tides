<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use Illuminate\Support\Facades\Gate;

class ArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.articles.index')->withArticles(Article::all());
    }

    /**
     * Store a newly created article in storage.
     */
    public function store(StoreArticleRequest $request)
    {
        $article = Article::create($request->validated());

        return to_route('articles.edit', $article);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.articles.create');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        return view('backend.articles.edit')->withArticle($article);
    }

    /**
     * Update the specified article in storage.
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        $article->update($request->validated());

        return to_route('articles.edit', $article);
    }

    /**
     * Remove the specified article from storage.
     */
    public function destroy(Article $article)
    {
        Gate::allowIf(fn ($user) => $user->isAdmin() || $user->isSuperadmin());

        $article->delete();

        return to_route('articles.index');
    }
}
