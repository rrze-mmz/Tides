<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Rules\NotificationBelongsToUser;
use Illuminate\Http\Request;

class UserNotificationsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function index()
    {
        return view('backend.users.notifications.index');
    }

    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'selected_notifications' => ['required', 'array', 'min:1'],
            'selected_notifications.*' => ['required', new NotificationBelongsToUser()],
        ]);

        Notification::whereIn('id', $validated['selected_notifications'])->delete();

        session()->flash('flashMessage', 'Selected notifications deleted successfully');

        return to_route('user.notifications');
    }
}
