<?php

namespace App\Notifications;

use App\Models\Series;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SeriesMembershipAddUser extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(protected Series $series)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject("You have been added to {$this->series->title} Series as moderator!")
            ->line("Hi {$notifiable->getFullNameAttribute()}")
            ->line('you have been added by '.auth()->user()->getFullNameAttribute().' as Series moderator')
            ->action('Check you series edit page', route('series.edit', $this->series))
            ->line('Thanks!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(mixed $notifiable): array
    {
        return [
            //
        ];
    }
}
