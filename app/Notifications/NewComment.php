<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class NewComment extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(protected Comment $comment)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $url =
            ($this->comment->type === 'frontend')
                ? $this->comment->commentable->path()
                : $this->comment->commentable->adminPath();

        return (new MailMessage)
            ->greeting('Hi '.$this->comment->commentable->owner?->getFullNameAttribute())
            ->line('There is a new comment on your
            '.Str::ucfirst($this->comment->commentable_type).' :'.$this->comment->commentable->title)
            ->line($this->comment->content)
            ->action('Go to comment', url($url.'#comments-section'))
            ->line('Thanks!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
