<?php

namespace App\Livewire;

use App\Jobs\IngestVideoFileToOpencast;
use App\Models\Clip;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class IngestOpencast extends Component
{
    use WithFileUploads;

    public $clip;

    public $videoFile;

    public $messageText;

    public $messageType;

    /**
     * Mount Livewire component
     */
    public function mount(Clip $clip)
    {
        $this->clip = $clip;
    }

    /**
     * Submit the form with the file and dispatch ingest to opencast job
     */
    public function submitForm(): void
    {
        $this->validate([
            'videoFile' => 'required|file|mimetypes:video/mp4,video/mpeg,video/x-matroska,video/x-m4v',
        ]);

        dispatch(new IngestVideoFileToOpencast($this->clip, $this->videoFile->getRealPath()));

        $this->messageText = 'Video file sent to Opencast for proccessing';
        $this->messageType = 'success';
    }

    /**
     * Render Livewire component
     */
    public function render(): View
    {
        return view('livewire.ingest-opencast');
    }
}
