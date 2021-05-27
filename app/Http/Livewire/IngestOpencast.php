<?php

namespace App\Http\Livewire;

use App\Jobs\IngestVideoFileToOpencast;
use App\Models\Clip;
use App\Services\OpencastService;
use Livewire\Component;
use Livewire\WithFileUploads;

class IngestOpencast extends Component
{
    use WithFileUploads;

    public $clip;
    public $videoFile;
    public $messageText;
    public $messageType;

    protected $rules = [
        'videoFile'  => 'required|file|mimetypes:video/mp4,video/mpeg,video/x-matroska,video/x-m4v'
    ];

    public function mount(Clip $clip)
    {
        $this->clip = $clip;
    }

    public function submitForm()
    {
        $this->validate();

        dispatch(new IngestVideoFileToOpencast($this->clip, $this->videoFile->getRealPath()));

        $this->messageText = 'Video file sent to Opencast for proccessing';
        $this->messageType = 'success';
    }

    public function render()
    {
        return view('livewire.ingest-opencast');
    }
}
