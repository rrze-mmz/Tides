<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class UsersController extends Controller
{
    use ResetsPasswords;

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

    /**
     * @throws Exception
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        $secret = random_int(20, 30);
        $validated['password'] = Hash::make($secret);

        $user = User::create($validated);

        Password::sendResetLink(['email' => $user->email]);

        return redirect(route('users.index'));
    }

    /**
     * @param User $user
     * @return View
     */
    public function edit(User $user): View
    {
        return view('backend.users.edit', compact('user'));
    }

    /**
     * @param User $user
     * @param UpdateUserRequest $request
     * @return RedirectResponse
     */
    public function update(User $user, UpdateUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user->update($validated);

        return redirect(route('users.edit', $user));
    }

    /**
     * @param User $user
     * @return Redirector|Application|RedirectResponse
     */
    public function destroy(User $user): Redirector|Application|RedirectResponse
    {
        $user->delete();

        return redirect(route('users.index'));
    }
}
