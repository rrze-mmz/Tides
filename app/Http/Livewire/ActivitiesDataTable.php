<?php

namespace App\Http\Livewire;

use App\Models\Activity;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ActivitiesDataTable extends Component
{
    use WithPagination;

    public $series = false;

    public $search = '';

    public $sortField;

    public $sortAsc = true;

    /**
     * Sort users by method parameter
     */
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

    /**
     * Updates the status of the component if series checkbox changed
     */
    public function updatingSeries(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        return view('livewire.activities-data-table', [
            'activities' => ($this->series)
                ? Activity::where('content_type', 'series')
                    ->where(function ($query) {
                        $query->search($this->search);
                    })->when($this->sortField, function ($query) {
                        $query->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');
                    })
                    ->orderBy('id', 'desc')
                    ->paginate(20)
                : Activity::search($this->search)
                    ->when($this->sortField, function ($query) {
                        $query->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');
                    })
                    ->orderBy('id', 'desc')
                    ->paginate(20),
        ]);
    }
}
