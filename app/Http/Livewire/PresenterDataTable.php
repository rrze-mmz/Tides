<?php

namespace App\Http\Livewire;

use App\Models\Presenter;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Contracts\View\View;

class PresenterDataTable extends Component
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

        return view('livewire.presenter-data-table', [
            'presenters' => Presenter::whereRaw('lower(first_name) like (?)', ["%{$search}%"])
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
