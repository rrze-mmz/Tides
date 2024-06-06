<?php

namespace App\Livewire;

use App\Models\Livestream;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class LivestreamsDataTable extends Component
{
    use WithPagination;

    public $search;

    public $sortField = 'active';

    public $sortAsc = false;

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

    public function render()
    {
        $search = trim(Str::lower($this->search));

        $livestreams = Livestream::search($search)
            ->when($this->sortField, function ($query) {
                $query->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');
            })
            ->paginate(40);

        return view('livewire.livestreams-data-table', ['livestreams' => $livestreams]);
    }
}
