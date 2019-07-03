<?php

namespace App\Notifications;

use App\Note;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NotePosted extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Note $note)
    {
        $this->note = $note;
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
        $user = $this->note->getSender();
        $userPic = $this->note->getSenderPicUrl();
        return new DatabaseMessage([
            'note_id'          => $this->note->id,
            'pic_url'         => $userPic,
            'message'         => "<strong>".$user."</strong>"." has posted a note on your board!",
            'action'          => '/note/'.$this->note->id
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
