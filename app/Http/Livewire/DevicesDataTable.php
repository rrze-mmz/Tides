<?php

namespace App\Http\Livewire;

use App\Models\Device;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class DevicesDataTable extends Component
{
    use WithPagination;

    public $search;

    public $sortField;

    public $sortAsc = true;

    protected $queryString = ['search', 'sortAsc'];

    public function sortBy($field): void
    {
        $this->sortAsc = ! ($this->sortField === $field) || ! $this->sortAsc;

        $this->sortField = $field;
    }

    /**
     * Updates the status of the component if search input changed
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render(): Factory|View|Application
    {
        $search = trim(Str::lower($this->search));

        $devices = Device::search($search)
            ->orWhereHas('location', function ($q) use ($search) {
                $q->WhereRaw("lower(name) like ('%{$search}%')");
            })->paginate(30);

        return view('livewire.devices-data-table', ['devices' => $devices]);
    }
}
