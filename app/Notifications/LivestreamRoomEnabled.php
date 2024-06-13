<?php

namespace App\Notifications;

use App\Models\Clip;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LivestreamRoomEnabled extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected Clip $clip)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = route('clips.edit', $this->clip);
        $livestreamName = $this->clip?->livestream->name;
        $seriesTitle = $this->clip->series->title;

        if (is_null($livestreamName)) {
            $livestreamName = 'Hidden livestream';
        }

        return (new MailMessage())
            ->subject("[VideoBot] - A livestream room : $livestreamName is for series: $seriesTitle reserved")
            ->greeting('Hello '.$notifiable->getFullNameAttribute())
            ->line("The link for the livestream backend is: $url")
            ->action('View livestream', url($url))
            ->salutation('Thanks');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
