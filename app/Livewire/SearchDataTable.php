<?php

namespace App\Livewire;

use App\Models\Series;
use App\Services\OpenSearchService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class SearchDataTable extends Component
{
    use WithPagination;

    public $search;

    public $sortField;

    public $sortAsc = true;

    public $page = 1;

    public $perPage = 150;

    public $total;

    protected $queryString = [
        'searchTerm' => ['except' => ''], // 'except' is optional, it defines the default state
    ];

    public function mount()
    {
        // You can also set initial values or defaults here
        $this->search = request()->query('term', $this->search);
    }

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

    public function loadMore()
    {
        $this->page++;
    }

    public function render(
        OpenSearchService $openSearchService
    ): View|Application|Factory|\Illuminate\Contracts\Foundation\Application {
        $currentPage = $this->page; // Livewire's built-in page property
        $pageSize = 10; // You can set this to whatever you like

        $searchResults = collect();
        $health = $openSearchService->getHealth();
        $results = [];
        $filters = [];
        $results['series'] = $openSearchService->searchIndexes(
            'tides_series',
            $this->search,
            $filters,
            $this->page,
            $this->perPage
        );
        if ($health->contains('pass')) {
            $results['series'] = $openSearchService->searchIndexes(
                'tides_series',
                $this->search,
                $filters,
                $this->page,
                $this->perPage);

            $results['series']['counter'] = ($results['series']->isNotEmpty())
                ? $results['series']['hits']['total']['value'] : [];
            $searchResults =
                $searchResults->put('series', $results['series'])
                    ->put('series_counter', $results['series']['counter']);
            $searchResults = $searchResults->put('searchTerm', $this->search);
        } else {
            $search = trim(Str::lower($this->search));
            $series = Series::search($search)->withLastPublicClip()->paginate(30)->withQueryString();
            $searchResults->put('series', $series)
                ->put('series_counter', $series->count());
        }

        return view('livewire.search-data-table', compact('searchResults'));
    }
}
