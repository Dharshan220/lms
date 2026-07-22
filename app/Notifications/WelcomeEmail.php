<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeEmail extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Welcome to Nano Spark LMS!')
            ->greeting('Welcome aboard, ' . $notifiable->name . '!')
            ->line('We are thrilled to have you join the Nano Spark learning community.')
            ->line('Start exploring courses, track your progress, and unlock achievements as you learn IoT, Robotics, AI and more.')
            ->action('Start Learning', url('/courses'))
            ->line('Complete your profile and dive into your first course today!')
            ->salutation('Best regards, Nano Spark LMS Team');
    }
}
