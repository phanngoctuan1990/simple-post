<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;

class Authenticated extends Notification
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
            ->markdown('mail.authenticated', ['url' => $url]);
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
        $url = url('/posts?' . $notifiable->name);

        return (new SlackMessage)
                    ->from('Ghost', ':ghost:')
                    ->to('@channel')
                    ->content('This will be sent to #other')
                    ->attachment(function ($attachment) use ($url) {
                        $attachment->title('Invoice 1322', $url)
                                   ->fields([
                                        'Title' => 'Server Expenses',
                                        'Amount' => '$1,234',
                                        'Via' => 'American Express',
                                        'Was Overdue' => ':-1:',
                                    ]);
                    });
    }
}
