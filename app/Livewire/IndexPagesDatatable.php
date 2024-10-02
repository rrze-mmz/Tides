<?php

namespace App\Livewire;

use App\Models\Clip;
use App\Models\Series;
use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Livewire\Component;
use Livewire\WithPagination;

class IndexPagesDatatable extends Component
{
    use WithPagination;

    public $search;

    public $sortField = 'id';

    public $sortAsc = true;

    public $type = 'series';

    public $singleClips = true;

    public $organization;

    public function sortBy($field): void
    {
        $this->sortAsc = ! ($this->sortField === $field) || ! $this->sortAsc;
        $this->sortField = $field;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $search = trim(Str::lower($this->search));
        $objects = $this->determineClipQuery($search)->paginate(20);

        return view('livewire.index-pages-datatable', [
            'type' => $this->type,
            'objs' => $objects,
            'search' => $search,
        ]);
    }

    protected function determineClipQuery($search)
    {
        return match ($this->type) {
            'series' => $this->seriesQuery($search),
            'clips' => $this->clipsQuery($search),
            'organization' => $this->organizationQuery($search),
            default => throw new InvalidArgumentException('Invalid type provided'),
        };
    }

    protected function seriesQuery($search)
    {
        return Series::query()
            ->with(['presenters'])
            ->withLastPublicClip()
            ->isPublic()
            ->whereHas('clips.assets')
            ->where($this->searchConditions($search))
            ->orderBy('id', 'desc');
    }

    protected function clipsQuery($search)
    {
        $query = Clip::query();
        if ($this->singleClips) {
            $query->Single();
        }

        return $query->with(['presenters'])
            ->public()
            ->where($this->searchConditions($search))
            ->orderBy('updated_at', 'desc');
    }

    protected function organizationQuery($search)
    {
        return Series::whereHas('organization', function ($q) {
            $string = Str::substr($this->organization->orgno, 0, 2);
            $q->whereRaw('orgno like ?', ["{$string}%"]);
        })
            ->with(['presenters'])
            ->withLastPublicClip()
            ->isPublic()
            ->whereHas('clips.assets')
            ->where($this->searchConditions($search))
            ->orderBy('id', 'desc');
    }

    protected function searchConditions($search): Closure
    {
        return function ($q) use ($search) {
            $q->where('id', (int) $search)
                ->orWhereRaw('lower(title) like ?', ["%{$search}%"])
                ->orWhereHas('presenters', function ($q) use ($search) {
                    $this->addPresenterSearchConditions($q, $search);
                });
        };
    }

    protected function addPresenterSearchConditions($query, $search): void
    {
        if (DB::getDriverName() === 'pgsql' || DB::getDriverName() === 'sqlite') {
            // PostgreSQL or SQLite concatenation using "||"
            $query->whereRaw('lower(first_name) like ?', ["%{$search}%"])
                ->orWhereRaw('lower(last_name) like ?', ["%{$search}%"])
                ->orWhereRaw('lower(first_name || \' \' || last_name) like ?', ["%{$search}%"])
                ->orWhereRaw('lower(last_name || \' \' || first_name) like ?', ["%{$search}%"]);
        } else {
            // MySQL or others using CONCAT()
            $query->whereRaw('lower(first_name) like ?', ["%{$search}%"])
                ->orWhereRaw('lower(last_name) like ?', ["%{$search}%"])
                ->orWhereRaw('lower(CONCAT(first_name, " ", last_name)) like ?', ["%{$search}%"])
                ->orWhereRaw('lower(CONCAT(last_name, " ", first_name)) like ?', ["%{$search}%"]);
        }
    }
}
