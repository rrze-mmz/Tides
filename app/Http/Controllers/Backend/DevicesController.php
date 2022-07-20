<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDeviceRequest;
use App\Http\Requests\UpdateDeviceRequest;
use App\Models\Device;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class DevicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     *
     * @throws AuthorizationException
     */
    public function index(): Application|Factory|View
    {
        Gate::allowIf(fn ($user) => $user->isAdmin() || $user->isAssistant());

        return view('backend.devices.index')->withDevices(Device::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     *
     * @throws AuthorizationException
     */
    public function create(): Application|Factory|View
    {
        Gate::allowIf(fn ($user) => $user->isAdmin() || $user->isAssistant());

        return view('backend.devices.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreDeviceRequest  $request
     * @return RedirectResponse
     */
    public function store(StoreDeviceRequest $request): RedirectResponse
    {
        $device = Device::create($request->validated());

        return to_route('devices.edit', $device);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Device  $device
     * @return Application|Factory|View
     */
    public function edit(Device $device): Application|Factory|View
    {
        Gate::allowIf(fn ($user) => $user->isAdmin() || $user->isAssistant());

        return view('backend.devices.edit', compact('device'));

        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateDeviceRequest  $request
     * @param  Device  $device
     * @return RedirectResponse
     */
    public function update(UpdateDeviceRequest $request, Device $device)
    {
        $device->update($request->validated());

        return to_route('devices.edit', $device);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Device  $device
     * @return RedirectResponse
     */
    public function destroy(Device $device): RedirectResponse
    {
        Gate::allowIf(fn ($user) => $user->isAdmin() || $user->isAssistant());

        $device->delete();

        return to_route('devices.index');
        //
    }
}
