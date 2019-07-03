<?php

namespace App\Notifications;

use App\UserGift;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class GiftReceived extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(UserGift $userGift)
    {
        $this->userGift = $userGift;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Define the database message to be stored
     *
     * @param $notifiable
     * @return DatabaseMessage
     */
    public function toDatabase($notifiable)
    {
        $user = $this->userGift->getSender();
        $gift = $this->userGift->gift;
        return new DatabaseMessage([
            'gift_id'         => $this->userGift->id,
            'pic_url'         => $gift->getPicUrl(),
            'message'         => "<strong>".$user."</strong>"." has sent you a ".$gift->title."!",
            'action'          => '/gifts/'.$this->userGift->id
        ]);
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
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
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
