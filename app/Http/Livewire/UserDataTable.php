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
    protected $queryString = ['search','admin', 'sortAsc'];

    public function sortBy($field)
    {
        $this->sortAsc = !($this->sortField === $field) || !$this->sortAsc;

        $this->sortField = $field;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingAdmin(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $search = trim(strtolower($this->search));

        return view('livewire.user-data-table', [
            'users' => ($this->admin)
                ?Role::where('name', 'admin')->first()
                        ->users()
                        ->where(function ($query) use ($search) {
                            $query->whereRaw('lower(name) like (?)', ["%{$search}%"])
                                ->orwhereRaw('lower(email) like (?)', ["%{$search}%"]);
                        })
                        ->when($this->sortField, function ($query) {
                            $query->orderBy($this->sortField, $this->sortAsc ? 'asc' :  'desc');
                        })
                        ->paginate(10)
                :User::whereRaw('lower(name) like (?)', ["%{$search}%"])
                    ->orwhereRaw('lower(email) like (?)', ["%{$search}%"])
                    ->when($this->sortField, function ($query) {
                        $query->orderBy($this->sortField, $this->sortAsc ? 'asc' :  'desc');
                    })
                    ->paginate(10),
        ]);
    }
}
