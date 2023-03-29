<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProfileCompletion extends Notification
{
    use Queueable;

    public $sender_alumni_id;
    public $profile_completion_percentage_amount;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($sender_alumni_id, $profile_completion_percentage_amount)
    {
        $this->sender_alumni_id = $sender_alumni_id;
        $this->profile_completion_percentage_amount = $profile_completion_percentage_amount;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    // public function via($notifiable)
    // {
    //     return ['mail'];
    // }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('Your profile needs to be updated')
            ->action('View Profile', url('http://127.0.0.1:5173/profile'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    // public function toArray($notifiable)
    // {
    //     return [
    //         //
    //     ];
    // }
    public function toDatabase($notifiable)
    {
        return [
            'sender_alumni_id' => $this->sender_alumni_id,
            'profile_completion_percentage' => $this->profile_completion_percentage_amount,
            'message' => 'Your profile needs to be updated',
        ];
    }
}
