<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCollectionRequest;
use App\Models\Collection;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CollectionsController extends Controller
{
    /*
     * Index all available collections
     */
    public function index(): Factory|View|Application
    {
        return view('backend.collections.index')->withCollections(Collection::all());
    }

    /**
     * Persists a collection to database
     */
    public function store(StoreCollectionRequest $request): RedirectResponse
    {
        return to_route('collections.edit', Collection::create($request->validated()));
    }

    /**
     * Render a create form for new collection
     */
    public function create(): Factory|View|Application
    {
        return view('backend.collections.create');
    }

    /**
     * Edit a single collection
     */
    public function edit(Collection $collection): Factory|View|Application
    {
        return view('backend.collections.edit')->withCollection($collection);
    }

        /**
         * Updates a collection record on database
         */
        public function update(Collection $collection, StoreCollectionRequest $request): RedirectResponse
        {
            $collection->update($request->validated());

            return to_route('collections.edit', $collection);
        }

    /**
     * Deletes a single collection
     */
    public function destroy(Collection $collection): RedirectResponse
    {
        $collection->delete();

        return to_route('collections.index');
    }
}
