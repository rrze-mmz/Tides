<?php

namespace App\Http\Livewire;

use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class UserDataTable extends Component
{
    use WithPagination;

    public $admin = false;
    public $search;
    public $sortField;
    public $sortAsc = true;
    protected $queryString = ['search', 'admin', 'sortAsc'];

    /**
     * Sort users by method parameter
     *
     * @param $field
     */
    public function sortBy($field): void
    {
        $this->sortAsc = !($this->sortField === $field) || !$this->sortAsc;

        $this->sortField = $field;
    }

    /**
     * Updates the status of the component if search input changed
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Updates the status of the component if  admin checkbox changed
     */
    public function updatingAdmin(): void
    {
        $this->resetPage();
    }

    /**
     * Render Livewire component
     * @return View
     */
    public function render(): View
    {
        $search = trim(strtolower($this->search));

        return view('livewire.user-data-table', [
            'users' => ($this->admin)
                ? Role::where('name', 'admin')->first()
                    ->users()
                    ->where(function ($query) use ($search) {
                        $query->whereRaw('lower(first_name) like (?)', ["%{$search}%"])
                            ->orwhereRaw('lower(last_name) like (?)', ["%{$search}%"])
                            ->orwhereRaw('lower(username) like (?)', ["%{$search}%"])
                            ->orwhereRaw('lower(email) like (?)', ["%{$search}%"]);
                    })
                    ->when($this->sortField, function ($query) {
                        $query->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');
                    })
                    ->paginate(10)
                : User::whereRaw('lower(first_name) like (?)', ["%{$search}%"])
                    ->orwhereRaw('lower(last_name) like (?)', ["%{$search}%"])
                    ->orwhereRaw('lower(username) like (?)', ["%{$search}%"])
                    ->orwhereRaw('lower(email) like (?)', ["%{$search}%"])
                    ->when($this->sortField, function ($query) {
                        $query->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');
                    })
                    ->paginate(10),
        ]);
    }
}
