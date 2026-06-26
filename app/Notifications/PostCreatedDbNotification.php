<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostCreatedDbNotification extends Notification {
    use Queueable;

    public $post;
    public $user;
    /**
     * Create a new notification instance.
     */
    public function __construct($post, $user)
    {
        $this->post = $post;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->greeting('Hello!')
            ->line('Just want to inform you that '.$this->user->name.' has created a new post.')
            ->action('Let\'s check it out', url('/posts/'.$this->post->uuid))
            ->line('Thank you for using our application!');
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

    public function toDatabase(object $notifiable)
    {
        return [
            'post_id' => $this->post->id,
            'post_uuid' => $this->post->uuid,
            'post_content' => $this->post->content,
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
        ];
    }
}
