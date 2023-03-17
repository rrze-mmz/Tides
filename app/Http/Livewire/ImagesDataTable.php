<?php

namespace App\Http\Livewire;

use App\Models\Image;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ImagesDataTable extends Component
{
    use WithPagination;

    public $search = '';

    public $sortField;

    public $sortAsc = true;

    protected $queryString = ['search', 'sortAsc'];

    public function sortBy($field): void
    {
        $this->sortAsc = ! ($this->sortField === $field) || ! $this->sortAsc;

        $this->sortField = $field;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        return view('livewire.images-data-table', [
            'images' => Image::search($this->search)
                ->when($this->sortField, function ($query) {
                    $query->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');
                })->paginate(30),
        ]);
    }
}
