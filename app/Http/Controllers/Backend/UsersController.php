<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;

class UsersController extends Controller {

    public function index(User $user)
    {
        $this->authorize('view', $user);

        return view('backend.users.datatables', [
            'users' => User::paginate(10)
            ]
        );
    }
}
