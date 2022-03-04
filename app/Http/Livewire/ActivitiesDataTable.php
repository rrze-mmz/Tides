<?php

namespace App\Http\Livewire;

use App\Models\Activity;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class ActivitiesDataTable extends Component
{
    use WithPagination;

    public $series = false;
    public $search;
    public $sortField;
    public $sortAsc = true;
    protected $queryString = ['search', 'series', 'sortAsc'];

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
     * Updates the status of the component if series checkbox changed
     */
    public function updatingSeries(): void
    {
        $this->resetPage();
    }

    public function render(): Factory|View|Application
    {
        $search = trim(Str::lower($this->search));

        return view('livewire.activities-data-table', [
            'activities' => ($this->series)
                ? Activity::where('content_type', 'series')
                    ->where(function ($query) use ($search) {
                        $query->whereRaw('lower(content_type) like (?)', ["%{$search}%"])
                            ->orwhereRaw('lower(change_message) like (?)', ["%{$search}%"])
                            ->orwhereRaw('lower(user_real_name) like (?)', ["%{$search}%"]);
                    })->when($this->sortField, function ($query) {
                        $query->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');
                    })
                    ->orderBy('id', 'desc')
                    ->paginate(20)
                : Activity::whereRaw('lower(content_type) like (?)', ["%{$search}%"])
                    ->orwhereRaw('lower(change_message) like (?)', ["%{$search}%"])
                    ->orwhereRaw('lower(user_real_name) like (?)', ["%{$search}%"])
                    ->when($this->sortField, function ($query) {
                        $query->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');
                    })
                    ->orderBy('id', 'desc')
                    ->paginate(20)
        ]);
    }
}
