<?php

namespace App\Livewire;

use App\Models\Clip;
use App\Models\Series;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
        if ($this->type === 'series') {
            return Series::query()
                ->with(['presenters'])
                ->withLastPublicClip()
                ->isPublic()
                ->whereHas('clips.assets')
                ->where(function ($q) use ($search) {
                    $search = strtolower($search);
                    $q->where('id', (int) $search)
                        ->orWhereRaw('lower(title) like ?', ["%{$search}%"])
                        ->orWhereHas('presenters', function ($q) use ($search) {
                            if (DB::getDriverName() === 'pgsql') {
                                // PostgresSQL concatenation using "||"
                                $q->whereRaw('lower(first_name) like ?', ["%{$search}%"])
                                    ->orWhereRaw('lower(last_name) like ?', ["%{$search}%"])
                                    ->orWhereRaw('lower(first_name || \' \' || last_name) like ?', ["%{$search}%"])
                                    ->orWhereRaw('lower(last_name || \' \' || first_name) like ?', ["%{$search}%"]);
                            } else {
                                // MySQL or others using CONCAT()
                                $q->whereRaw('lower(first_name) like ?', ["%{$search}%"])
                                    ->orWhereRaw('lower(last_name) like ?', ["%{$search}%"])
                                    ->orWhereRaw('lower(CONCAT(first_name, " ", last_name)) like ?', ["%{$search}%"])
                                    ->orWhereRaw('lower(CONCAT(last_name, " ", first_name)) like ?', ["%{$search}%"]);
                            }
                        });
                })
                ->orderBy('id', 'desc');
        } elseif ($this->type === 'clips') {
            $query = Clip::query();
            if ($this->singleClips) {
                $query->Single();
            }

            return $query->with(['presenters'])
                ->Public()
                ->where(function ($q) use ($search) {
                    $search = strtolower($search);
                    $q->where('id', (int) $search)
                        ->orWhereRaw('lower(title) like ?', ["%{$search}%"])
                        ->orWhereHas('presenters', function ($q) use ($search) {
                            if (DB::getDriverName() === 'pgsql') {
                                // PostgresSQL concatenation using "||"
                                $q->whereRaw('lower(first_name) like ?', ["%{$search}%"])
                                    ->orWhereRaw('lower(last_name) like ?', ["%{$search}%"])
                                    ->orWhereRaw('lower(first_name || \' \' || last_name) like ?', ["%{$search}%"])
                                    ->orWhereRaw('lower(last_name || \' \' || first_name) like ?', ["%{$search}%"]);
                            } else {
                                // MySQL or others using CONCAT()
                                $q->whereRaw('lower(first_name) like ?', ["%{$search}%"])
                                    ->orWhereRaw('lower(last_name) like ?', ["%{$search}%"])
                                    ->orWhereRaw('lower(CONCAT(first_name, " ", last_name)) like ?', ["%{$search}%"])
                                    ->orWhereRaw('lower(CONCAT(last_name, " ", first_name)) like ?', ["%{$search}%"]);
                            }
                        });
                })
                ->orderBy('updated_at', 'desc');
        } elseif ($this->type === 'organization') {
            return Series::whereHas('organization', function ($q) {
                $string = Str::substr($this->organization->orgno, 0, 2);
                $q->whereRaw('orgno  like (?)', ["{$string}%"]);
            })->with(['presenters'])
                ->withLastPublicClip()
                ->isPublic()
                ->whereHas('clips.assets')
                ->where(function ($q) use ($search) {
                    $search = strtolower($search);
                    $q->where('id', (int) $search)
                        ->orWhereRaw('lower(title) like ?', ["%{$search}%"])
                        ->orWhereHas('presenters', function ($q) use ($search) {
                            if (DB::getDriverName() === 'pgsql') {
                                // PostgresSQL concatenation using "||"
                                $q->whereRaw('lower(first_name) like ?', ["%{$search}%"])
                                    ->orWhereRaw('lower(last_name) like ?', ["%{$search}%"])
                                    ->orWhereRaw('lower(first_name || \' \' || last_name) like ?', ["%{$search}%"])
                                    ->orWhereRaw('lower(last_name || \' \' || first_name) like ?', ["%{$search}%"]);
                            } else {
                                // MySQL or others using CONCAT()
                                $q->whereRaw('lower(first_name) like ?', ["%{$search}%"])
                                    ->orWhereRaw('lower(last_name) like ?', ["%{$search}%"])
                                    ->orWhereRaw('lower(CONCAT(first_name, " ", last_name)) like ?', ["%{$search}%"])
                                    ->orWhereRaw('lower(CONCAT(last_name, " ", first_name)) like ?', ["%{$search}%"]);
                            }
                        });
                })
                ->orderBy('id', 'desc');
        }
    }
}
