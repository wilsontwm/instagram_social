<?php

namespace App\Notifications;

use App\GiftComment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class GiftCommented extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(GiftComment $giftComment)
    {
        $this->giftComment = $giftComment;
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
        $user = $this->giftComment->user->name;
        $userPic = $this->giftComment->user->getProfileImageUrl();
        $gift = $this->giftComment->userGift;
        return new DatabaseMessage([
            'gift_id'         => $gift->id,
            'pic_url'         => $userPic,
            'message'         => "<strong>".$user."</strong>"." has sent you a comment on your gift!",
            'action'          => '/gifts/'.$gift->id
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
