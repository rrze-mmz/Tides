<?php

namespace App\Mail;

use App\Models\Clip;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VideoUploaded extends Mailable
{
    use Queueable, SerializesModels;

    protected $clip;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Clip $clip)
    {
        $this->clip = $clip;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Cool your video is now online')
                         ->markdown('email.clips.video_is_uploaded',[
                    'clip' => $this->clip
                    ]);
    }
}
