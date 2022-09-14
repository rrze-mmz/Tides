<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Slides\Saml2\Models\Tenant;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return Application|Factory|\Illuminate\Contracts\View\View
     */
    public function create(): \Illuminate\Contracts\View\View|Factory|Application
    {
        $saml2TenantUUID = (Tenant::count() > 0) ? Tenant::all()->first()->uuid : null;
        
        return view('auth.select-login', ['saml2TenantUUID' => $saml2TenantUUID]);
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param LoginRequest $request
     * @return RedirectResponse
     *
     * @throws ValidationException
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

//        return to_route('saml.logout');
        return redirect('/');
    }
}
