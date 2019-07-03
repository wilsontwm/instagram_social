<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class Note extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sender_id', 'recipient_id', 'content', 'color', 'is_private', 'is_pinned'
    ];

    /*
     * Get the timestamp when the note is sent
     */
    public function getDateTime()
    {
        $date = Carbon::parse($this->created_at)->diffForHumans();
        return $date;
    }

    /**
     * Get the name of sender of the note
     * @return String
     */
    public function getSender()
    {
        if (count($this->sender()->first()) > 0)
            return $this->sender()->first()->name;
        else
            return 'Anonymous';
    }

    /**
     * Get the profile url of sender of the note
     * @return URL
     */
    public function getSenderUrl()
    {
        if (count($this->sender()->first()) > 0)
            return URL::to('/profile/'.$this->sender()->first()->username);
        else
            return URL::to('#');
    }

    /**
     * Get the profile pic of sender of the note
     * @return URL
     */
    public function getSenderPicUrl()
    {
        if (count($this->sender()->first()) > 0)
            return URL::to($this->sender->user_pic);
        else
            return URL::to('/img/profiles/default.png');
    }

    /**
     *  A flag that determines if the user can view the note
     *  1. Public note will be viewable to everyone
     *  2. Private note will only be viewable to sender/recipient
     *  @return Boolean
     */
    public function canView()
    {
        return !$this->is_private ||
                ( Auth::user() && ( $this->sender_id === Auth::id() || $this->recipient_id === Auth::id() ) );
    }

    /**
     *  A flag that determines if the user can delete the note
     *  @return Boolean
     */
    public function canDelete()
    {
        return Auth::user() && ( $this->sender_id === Auth::id() || $this->recipient_id === Auth::id() );
    }
    /**
     * Return a flag to indicates if the note belongs to the user
     * @return bool
     */
    public function isOwned()
    {
        return Auth::user() && $this->recipient_id === Auth::id();
    }
    /*
    |--------------------------------------------------------------------------
    | Relationship Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Many-To-One Relationship Method for accessing the sender
     *
     * @return QueryBuilder Object
     */
    public function sender()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Many-To-One Relationship Method for accessing the recipient
     *
     * @return QueryBuilder Object
     */
    public function recipient()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * One-To-Many Relationship Method for accessing the comments
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('App\NoteComment');
    }
}
