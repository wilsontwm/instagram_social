<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class UserGift extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_gifts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'sender_id', 'gift_id', 'message', 'price'
    ];

    /**
     * Returns the URL of the profile picture of receiver, default if none.
     *
     * @return mixed
     */
    public function getReceiverProfileImageUrl()
    {
        if (count($this->user()->first()) > 0){
            return $this->user->getProfileImageUrl();
        }
        return URL::to('/img/profiles/default.png');
    }

    /**
     * Get the name of the receiver of the gift
     * @return string
     */
    public function getReceiver()
    {
        if($receiver = $this->user) {
            return $receiver->name;
        }

        return 'Anonymous';
    }

    /**
     * Returns the URL of the profile picture of sender, default if none.
     *
     * @return mixed
     */
    public function getSenderProfileImageUrl()
    {
        if (count($this->sender()->first()) > 0){
            return $this->sender->getProfileImageUrl();
        }
        return URL::to('/img/profiles/default.png');
    }

    /**
     * Get the name of the sender of the gift
     * @return string
     */
    public function getSender()
    {
        if($sender = $this->sender) {
            return $sender->name;
        }

        return 'Anonymous';
    }

    /**
     * Get the profile url of sender of the gift
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
     * Get the date time of the gift
     * @return string
     */
    public function getDateTime()
    {
        $date = Carbon::parse($this->created_at)->diffForHumans();
        return $date;
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
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Many-To-One Relationship Method for accessing the gift
     *
     * @return QueryBuilder Object
     */
    public function gift()
    {
        return $this->belongsTo('App\Gift');
    }

    /**
     * One-To-Many Relationship Method for accessing the comments
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('App\GiftComment');
    }
}
