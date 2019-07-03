<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\URL;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The constant for the value representation of gender
     */
    const ROLE_SUPERADMIN = 0;
    const ROLE_ADMIN = 1;
    const ROLE_USER = 2;
    const ROLE_ARRAY = [
        self::ROLE_SUPERADMIN => 'Super Admin',
        self::ROLE_ADMIN => 'Admin',
        self::ROLE_USER => 'User'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'instagram_id', 'username', 'user_pic', 'role', 'credit_balance', 'gift_data'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'instagram_id'
    ];

    /**
     * Returns boolean to check if user has set up profile picture
     *
     * @return mixed
     */
    public function hasProfileImage()
    {
        return $this->user_pic !== null;
    }

    /**
     * Returns the URL of the profile picture, default if none.
     *
     * @return mixed
     */
    public function getProfileImageUrl()
    {
        if ($this->hasProfileImage()){
            return URL::to($this->user_pic);
        }
        return URL::to('/img/profiles/default.png');
    }

    /**
     * Get the role name of the user
     * @return array
     */
    public function getRole()
    {
        return self::ROLE_ARRAY[$this->role];
    }

    /**
     * A flag that indicates if the user is super admin
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->role == User::ROLE_SUPERADMIN;
    }

    /**
     * A flag that indicates if the user is admin
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role == User::ROLE_ADMIN || $this->role == User::ROLE_SUPERADMIN;
    }

    /**
     * A flag that indicates if the user is normal user
     * @return bool
     */
    public function isUser()
    {
        return $this->role == User::ROLE_USER;
    }

    /**
     * Return a date when the user is registered
     * @return string
     */
    public function getRegisteredDate()
    {
        $date = Carbon::parse($this->created_at);
        return $date->format('F j\\, Y');
    }

    /**
     * A flag that indicates if the user is new user
     * @return bool
     */
    public function isNewUser()
    {
        $beforeDate = Carbon::today()->subDays(3);
        $registeredDate = Carbon::parse($this->created_at);
        return $registeredDate->greaterThanOrEqualTo($beforeDate);
    }
    /*
    |--------------------------------------------------------------------------
    | Relationship Methods
    |--------------------------------------------------------------------------
    */

    /**
     * One-To-Many Relationship Method for accessing the User->socialAccounts
     *
     * @return QueryBuilder Object
     */
    public function socialAccounts()
    {
        return $this->hasMany('App\SocialAccount');
    }

    /**
     * One-To-Many Relationship Method for accessing the sent notes
     *
     * @return QueryBuilder Object
     */
    public function sentNotes()
    {
        return $this->hasMany('App\Note', 'sender_id');
    }

    /**
     * One-To-Many Relationship Method for accessing the received notes
     *
     * @return QueryBuilder Object
     */
    public function receivedNotes()
    {
        return $this->hasMany('App\Note', 'recipient_id');
    }

    /**
     * Many-To-Many Relationship Method for accessing the gift count
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function gifts()
    {
        return $this->belongsToMany('App\Gift')->withPivot('count');
    }

    /**
     * One-To-Many Relationship Method for accessing the sent gifts
     *
     * @return QueryBuilder Object
     */
    public function sentGifts()
    {
        return $this->hasMany('App\UserGift', 'sender_id');
    }

    /**
     * One-To-Many Relationship Method for accessing the received gifts
     *
     * @return QueryBuilder Object
     */
    public function receivedGifts()
    {
        return $this->hasMany('App\UserGift', 'user_id');
    }

    /**
     * One-To-Many Relationship Method for accessing the user's transactions
     *
     * @return QueryBuilder Object
     */
    public function transactions()
    {
        return $this->hasMany('App\UserTransaction', 'user_id');
    }

    /**
     * One-To-Many Relationship Method for accessing the user's cashout requests
     *
     * @return QueryBuilder Object
     */
    public function cashoutRequests()
    {
        return $this->hasMany('App\CashoutRequest', 'user_id');
    }


}
