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
     * @param User $user
     * @return View
     * @throws AuthorizationException
     */
    public function index(User $user): View
    {
        $this->authorize('view', $user);

        return view('backend.users.datatables', [
            'users' => User::paginate(10)
        ]);
    }
}
