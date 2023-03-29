<?php

namespace App\Notifications;

use App\Mail\ForgotPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;

class ResetPasswordApi extends VerifyEmailBase
{
    use Queueable;

    protected $url;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return ForgotPassword
     */
    public function toMail($notifiable)
    {
        return (new ForgotPassword($this->url))
            ->to( $notifiable->email );
    }
}
