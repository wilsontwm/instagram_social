<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class GiftComment extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'gift_comments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_gift_id', 'user_id', 'reply_user_id', 'reply_id', 'comment', 'is_deleted'];

    /**
     * Get the date time of the comment
     * @return string
     */
    public function getDateTime()
    {
        $date = Carbon::parse($this->created_at)->diffForHumans();
        return $date;
    }

    /**
     * A flag that indicates if the user can delete the comment
     * @param $user
     * @return bool
     */
    public function canDelete($user)
    {
        return $user && $user->id == $this->user->id && !$this->is_deleted;
    }

    /**
     * Get the name of the user of the comment replied to
     * @return string
     */
    public function repliedUserName()
    {
        if($replyTo = $this->replyUser) {
            return $replyTo->name;
        }

        return '';
    }

    /**
     * Get the profile url of sender of the gift comment
     * @return URL
     */
    public function getSenderUrl()
    {
        if (count($this->user()->first()) > 0)
            return URL::to('/profile/'.$this->user()->first()->username);
        else
            return URL::to('#');
    }

    /**
     * A flag that indicates if the comment is replying to the main post
     * @return bool
     */
    public function isParent()
    {
        return $this->reply_id == null;
    }

    /*
    |--------------------------------------------------------------------------
    | Relationship Methods
    |--------------------------------------------------------------------------
    */
    /**
     * Relationship method for accessing the User Gift
     */
    public function userGift()
    {
        return $this->belongsTo('App\UserGift');
    }

    /**
     * Relationship method for accessing the User
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Relationship method for accessing the GiftComment that it replies to
     */
    public function replyTo()
    {
        return $this->belongsTo('App\GiftComment', 'reply_id');
    }

    /**
     * Relationship method for accessing the User that it replies to
     */
    public function replyUser()
    {
        return $this->belongsTo('App\User', 'reply_user_id');
    }

    /**
     * Relationship method for accessing the GiftComment replied
     */
    public function replies()
    {
        return $this->hasMany('App\GiftComment', 'reply_id');
    }
}
