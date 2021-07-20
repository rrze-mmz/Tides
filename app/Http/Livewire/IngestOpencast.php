<?php

namespace App\Http\Livewire;

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
    protected array $rules = [
        'videoFile' => 'required|file|mimetypes:video/mp4,video/mpeg,video/x-matroska,video/x-m4v'
    ];

    /**
     * Mount Livewire component
     *
     * @param Clip $clip
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
        $this->validate();

        dispatch(new IngestVideoFileToOpencast($this->clip, $this->videoFile->getRealPath()));

        $this->messageText = 'Video file sent to Opencast for proccessing';
        $this->messageType = 'success';
    }

    /**
     * Render Livewire component
     * @return View
     */
    public function render(): View
    {
        return view('livewire.ingest-opencast');
    }
}
