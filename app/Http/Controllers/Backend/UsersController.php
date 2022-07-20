<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

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
            'users' => User::paginate(10),
        ]);
    }

    /**
     * Create form for a user
     *
     * @return View
     */
    public function create(): View
    {
        return view('backend.users.create');
    }

    /**
     * Store a user in database
     *
     * @throws Exception
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        $secret = random_int(20, 30);
        $validated['password'] = Hash::make((string) $secret);

        $user = User::create($validated);

        Password::sendResetLink(['email' => $user->email]);

        return to_route('users.index');
    }

    /**
     * Edit form for a user
     *
     * @param  User  $user
     * @return View
     */
    public function edit(User $user): View
    {
        return view('backend.users.edit', compact('user'));
    }

    /**
     * Updates a single user in database
     *
     * @param  User  $user
     * @param  UpdateUserRequest  $request
     * @return RedirectResponse
     */
    public function update(User $user, UpdateUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user->update($validated);

        $user->assignRole(Role::find($validated['role_id'])->name);

        return to_route('users.edit', $user);
    }

    /**
     * Deletes a single user
     *
     * @param  User  $user
     * @return RedirectResponse
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return to_route('users.index');
    }
}
