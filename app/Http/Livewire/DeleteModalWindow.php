<?php

namespace App\Http\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class DeleteModalWindow extends Component
{
    use AuthorizesRequests;

    public $model;

    public $showModal = false;

    public function render()
    {
        return view('livewire.delete-modal-window');
    }

    public function delete()
    {
        //Only portal admins can delete images.
        $this->authorize('administrate-admin-portal-pages');

        Storage::disk('images')->delete($this->model->file_name);
        Storage::disk('images')->delete('Thumbnails/'.$this->model->file_name);
        $this->model->delete();

        return to_route('images.index');
    }
}
