<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePresenterRequest;
use App\Models\Presenter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PresentersController extends Controller
{
    /*
     * Render datatables Livewire component
     *
     * @return View
     */
    public function index(): View
    {
        Gate::allowIf(fn ($user) => $user->isAdmin() || $user->isAssistant());

        return view('backend.presenters.index', [
            'presenters' => Presenter::paginate(10),
        ]);
    }

    /**
     * @return View
     */
    public function create(): View
    {
        Gate::allowIf(fn ($user) => $user->isAdmin() || $user->isAssistant());

        return view('backend.presenters.create');
    }

    /**
     * Store a presenter in database
     *
     * @param  StorePresenterRequest  $request
     * @return RedirectResponse
     */
    public function store(StorePresenterRequest $request): RedirectResponse
    {
        Gate::allowIf(fn ($user) => $user->isAdmin() || $user->isAssistant());

        Presenter::create($request->validated());

        return to_route('presenters.index');
    }

    /**
     * Edit form for a presenter
     *
     * @param  Presenter  $presenter
     * @return View
     */
    public function edit(Presenter $presenter): View
    {
        Gate::allowIf(fn ($user) => $user->isAdmin() || $user->isAssistant());

        return view('backend.presenters.edit', compact('presenter'));
    }

    /**
     * Update a single presenter in database
     *
     * @param  Presenter  $presenter
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function update(Presenter $presenter, Request $request): RedirectResponse
    {
        Gate::allowIf(fn ($user) => $user->isAdmin() || $user->isAssistant());

        $validated = $request->validate([
            'academic_degree_id' => ['integer'],
            'first_name' => ['required', 'alpha', 'min:2', 'max:30'],
            'last_name' => ['required', 'alpha', 'min:2', 'max:100'],
            'username' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('presenters')->ignore($presenter),
            ],
            'email' => [
                'email',
                Rule::unique('presenters')->ignore($presenter), ],
        ]);

        $presenter->update($validated);

        return to_route('presenters.edit', $presenter);
    }

    /**
     * Deletes a single presenter
     *
     * @param  Presenter  $presenter
     * @return RedirectResponse
     */
    public function destroy(Presenter $presenter): RedirectResponse
    {
        Gate::allowIf(fn ($user) => $user->isAdmin() || $user->isAssistant());

        $presenter->delete();

        return to_route('presenters.index');
    }
}
