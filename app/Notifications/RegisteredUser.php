<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;

class RegisteredUser extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable Notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        $notifiable = $notifiable;
        return ['mail', 'database', 'slack'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable Notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = url('/posts?' . $notifiable->name);

        return (new MailMessage)
                    ->subject('Wellcome new member')
                    ->greeting('Hello!')
                    ->line('Thanks now you can use application when you click button bellow.')
                    ->action('Go to Application', $url)
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable Notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return $notifiable->toArray();
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param mixed $notifiable Notifiable
     *
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        $notifiable = $notifiable;
        return (new SlackMessage)
                    ->from('Ghost', ':ghost:')
                    ->to('#other')
                    ->content('This will be sent to #other');
    }
}
