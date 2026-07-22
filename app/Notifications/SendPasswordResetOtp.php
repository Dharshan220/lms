<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendPasswordResetOtp extends Notification
{
    use Queueable;

    public function __construct(
        public string $otp,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Password Reset Code - Nano Spark LMS')
            ->greeting('Hello, ' . $notifiable->name . '!')
            ->line('We received a request to reset your password for your Nano Spark LMS account.')
            ->line('Use the verification code below to proceed:')
            ->line('**' . $this->otp . '**')
            ->line('This code will expire in 10 minutes.')
            ->line('If you did not request a password reset, please ignore this email.')
            ->salutation('Best regards, Nano Spark LMS Team');
    }
}
