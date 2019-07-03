<?php

namespace App\Notifications;


use App\NoteComment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NoteReplied extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(NoteComment $noteComment)
    {
        $this->noteComment = $noteComment;
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
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        /*return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');*/
    }

    /**
     * Define the database message to be stored
     *
     * @param $notifiable
     * @return DatabaseMessage
     */
    public function toDatabase($notifiable)
    {
        $user = $this->noteComment->user->name;
        $userPic = $this->noteComment->user->getProfileImageUrl();
        $noteId = $this->noteComment->note->id;
        return new DatabaseMessage([
            'note_id'          => $noteId,
            'pic_url'         => $userPic,
            'message'         => "<strong>".$user."</strong>"." has left you a note reply!",
            'action'          => '/note/'.$noteId
        ]);
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
