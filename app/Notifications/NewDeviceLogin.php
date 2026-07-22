<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewDeviceLogin extends Notification
{
    use Queueable;

    public function __construct(
        public string $ip,
        public string $userAgent,
        public string $time,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Login to Your Nano Spark Account')
            ->greeting('Hello, ' . $notifiable->name . '!')
            ->line('We detected a new login to your Nano Spark LMS account.')
            ->line('**IP Address:** ' . $this->ip)
            ->line('**Browser / Device:** ' . $this->userAgent)
            ->line('**Time:** ' . $this->time)
            ->line('If this was you, you can ignore this email.')
            ->lineIf(! $notifiable->hasVerifiedEmail(), 'If you do not recognise this activity, please reset your password immediately and enable email verification.')
            ->action('Account Settings', url('/profile'))
            ->salutation('Best regards, Nano Spark LMS Team');
    }
}
