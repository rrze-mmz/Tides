<?php

namespace App\Livewire;

use App\Models\Clip;
use App\Models\Semester;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class ClipsDataTable extends Component
{
    use WithPagination;

    public $userClips = false;

    public $search;

    public $sortField = 'recording_date';

    public $sortAsc = false;

    public $selectedSemesterID;

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
        $clips = $this->determineClipQuery($search)->paginate(30);

        return view('livewire.clips-data-table', [
            'clips' => $clips,
            'semestersList' => Semester::orderBy('id', 'desc')->get(),
        ]);
    }

    protected function determineClipQuery($search)
    {
        $query = $this->userClips ? $this->userClipsQuery($search) : $this->adminOrDefaultQuery($search);

        // Apply semester filter if a semester is selected
        if ($this->selectedSemesterID) {
            $query->where('semester_id', $this->selectedSemesterID);
        }

        return $query;
    }

    protected function userClipsQuery($search)
    {
        return auth()->user()->clips()->search($search)
            ->when($this->sortField, fn ($query) => $query->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc'));
    }

    protected function adminOrDefaultQuery($search)
    {
        $query = Clip::query();

        if (auth()->user()->can('administrate-portal-pages')) {
            $query = $query->search($search)
                ->where('id', (int) $search)
                ->orWhere(function ($query) use ($search) {
                    $query->whereHas('presenters', function ($q) use ($search) {
                        $q->whereRaw('lower(first_name) like ?', ["%{$search}%"])
                            ->orWhereRaw('lower(last_name) like ?', ["%{$search}%"]);
                    })
                        ->orWhereDoesntHave('presenters');
                })
                ->orWhere(function ($query) use ($search) {
                    $query->whereHas('series', function ($q) use ($search) {
                        $q->whereRaw('lower(title) like ?', ["%{$search}%"])
                            ->orWhereRaw('lower(description) like ?', ["%{$search}%"]);
                    })
                        ->orWhereDoesntHave('series');
                });
        } else {
            $query = $query->with(['assets', 'presenters'])
                ->public()
                ->whereHas('assets')
                ->orWhere('id', (int) $search)
                ->search($search)
                ->orWhere(function ($query) use ($search) {
                    $query->whereHas('presenters', function ($q) use ($search) {
                        $q->whereRaw('lower(first_name) like ?', ["%{$search}%"])
                            ->orWhereRaw('lower(last_name) like ?', ["%{$search}%"]);
                    })
                        ->orWhereDoesntHave('presenters');
                })
                ->orWhere(function ($query) use ($search) {
                    $query->whereHas('series', function ($q) use ($search) {
                        $q->whereRaw('lower(title) like ?', ["%{$search}%"])
                            ->orWhereRaw('lower(description) like ?', ["%{$search}%"]);
                    })
                        ->orWhereDoesntHave('series');
                });
        }

        return $query
            ->when($this->sortField, fn ($query) => $query->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc'));
    }
}
