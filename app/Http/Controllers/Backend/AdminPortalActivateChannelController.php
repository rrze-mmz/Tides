<?php

namespace App\Http\Controllers\Backend;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminPortalActivateChannelController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'exists:users'],
        ]);
        $user = User::search($validated['username'])->first();

        if (! $user->hasRole(Role::MODERATOR)) {
            $flashMessage = 'The user does not have a role: moderator';
            throw new AuthorizationException($flashMessage);
        } else {
            Channel::create([
                'url_handle' => '@'.Str::before($user->email, '@'),
                'name' => $user->getFullNameAttribute(),
                'description' => '',
                'banner_url' => null,
                'owner_id' => $user->id,
            ]);
            $flashMessage = 'User channel activated successfully';
        }

        return to_route('users.edit', $user)->with('flashMessage', $flashMessage);
    }
}
