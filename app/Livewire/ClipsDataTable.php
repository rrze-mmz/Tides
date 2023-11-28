<?php

namespace App\Livewire;

use App\Models\Clip;
use App\Models\Semester;
use Debugbar;
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

        Debugbar::info($query->toSql());
        // Apply semester filter if a semester is selected
        if ($this->selectedSemesterID) {
            Debugbar::info($this->selectedSemesterID);
            $query->where('semester_id', $this->selectedSemesterID);
        }

        Debugbar::info($query->toSql());

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

        if (auth()->user()->isAdmin()) {
            $query = $query->search($search)
                ->orWhere('id', (int) $search)
                ->orWhereHas('presenters', fn ($q) => $q->whereRaw('lower(first_name) like (?)', ["%{$search}%"])
                    ->orWhereRaw('lower(last_name) like (?)', ["%{$search}%"]))
                ->orWhereHas('series', fn ($q) => $q->whereRaw('lower(title) like (?)', ["%{$search}%"])
                    ->orWhereRaw('lower(description) like (?)', ["%{$search}%"]));
        } else {
            $query = $query->with(['assets', 'presenters'])
                ->public()
                ->orWhere('id', (int) $search)
                ->whereHas('assets')
                ->search($search)
                ->orWhereHas('presenters', fn ($q) => $q->whereRaw('lower(first_name) like (?)', ["%{$search}%"])
                    ->orWhereRaw('lower(last_name) like (?)', ["%{$search}%"]))
                ->orWhereHas('series', fn ($q) => $q->whereRaw('lower(title) like (?)', ["%{$search}%"])
                    ->orWhereRaw('lower(description) like (?)', ["%{$search}%"]));
        }

        return $query->when($this->sortField, fn ($query) => $query->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc'));
    }
}
