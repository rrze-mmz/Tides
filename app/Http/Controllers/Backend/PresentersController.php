<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePresenterRequest;
use App\Models\Presenter;
use Illuminate\Http\RedirectResponse;
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
        return view('backend.presenters.index', [
            'presenters' => Presenter::paginate(10)
        ]);
    }

    /**
     * @return View
     */
    public function create(): View
    {
        return view('backend.presenters.create');
    }

    /**
     * Store a presenter in database
     * @param StorePresenterRequest $request
     * @return RedirectResponse
     */
    public function store(StorePresenterRequest $request): RedirectResponse
    {
        $presenter = Presenter::create($request->validated());

        return redirect(route('presenters.index'));
    }
}
