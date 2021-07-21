<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;

class UsersController extends Controller
{
    /**
     * Render datatables Livewire component
     *
     * @return View
     */
    public function index(): View
    {
        return view('backend.users.index', [
            'users' => User::paginate(10)
        ]);
    }

    /**
     * Create user form
     *
     * @return View
     */
    public function create(): View
    {
        return view('backend.users.create');
    }
}
