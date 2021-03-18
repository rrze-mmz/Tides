<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Dashboard for the logged in user
     *
     * @return View
     */
    public function __invoke(): View
    {
        return view('backend.dashboard.index', [
            'clips' => auth()->user()->clips()->orderByDesc('updated_at')->limit(10)->get(),
        ]);
    }
}
