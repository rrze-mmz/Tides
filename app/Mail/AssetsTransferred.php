<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AssetsTransferred extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(protected Model $model) {}

    /**
     * Sends an email message if a video is uploaded successfully.
     *
     * @return $this
     */
    public function build(): static
    {
        return $this->subject('Cool your video is now online')
            ->markdown('email.clips.video_is_uploaded', [
                'clip' => $this->model,
            ]);
    }
}
