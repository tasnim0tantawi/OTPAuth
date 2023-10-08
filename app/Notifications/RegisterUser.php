<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegisterUser extends Notification implements ShouldQueue  
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
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

        $otp_object = new \Otp;
        $otp = $otp_object->generate($notifiable->email, 4, 15);

        return (new MailMessage)
                    ->line('Hi, ' . $notifiable->name)
                    ->line('Welcome to our application!')
                    ->line('Your account has been created successfully.')
                    ->line('OTP for ' . $notifiable->email)
                    ->line($otp->token)
                    ->line('OTP will expire in 15 minutes.')
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
}
