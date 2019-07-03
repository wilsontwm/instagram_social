<?php

namespace App\Notifications;

use App\CashoutRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;

class CashoutProcessed extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(CashoutRequest $cashoutRequest)
    {
        $this->cashoutRequest = $cashoutRequest;
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

        return new DatabaseMessage([
            'cashout_id'      => $this->cashoutRequest->id,
            'pic_url'         => URL::to('/img/cashout/cash.png'),
            'message'         => "Your cashout on ".$this->cashoutRequest->getDateTime()." has been processed",
            'action'          => '/cashout/'.$this->cashoutRequest->id
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
