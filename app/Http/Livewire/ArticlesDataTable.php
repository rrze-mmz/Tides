<?php

namespace App\Http\Livewire;

use App\Models\Article;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class ArticlesDataTable extends Component
{
    use WithPagination;

    public $search;

    public $sortField;

    public $sortAsc = true;

    public function sortBy($field): void
    {
        $this->sortAsc = ! ($this->sortField === $field) || ! $this->sortAsc;

        $this->sortField = $field;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render(): Factory|View|Application
    {
        $search = trim(Str::lower($this->search));

        $articles = Article::search($search)
            ->paginate(30);

        return view('livewire.articles-data-table', ['articles' => $articles]);
    }
}
