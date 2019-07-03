<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class Gift extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'gifts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'price', 'pic_url', 'status'
    ];
    /**
     * The constant for the value representation of status
     */
    const STATUS_AVAILABLE = 0;
    const STATUS_DISABLED = 1;
    const STATUS_ARCHIVED = 2;
    const STATUS_ARRAY = [
        self::STATUS_AVAILABLE    => 'Available',
        self::STATUS_DISABLED     => 'Disabled',
        self::STATUS_ARCHIVED     => 'Archived'
    ];

    /**
     * Returns the URL of the gift picture, default if none.
     *
     * @return mixed
     */
    public function getPicUrl()
    {
        if ($image = $this->pic_url) {
            if (!starts_with($this->pic_url, ['http://', 'https://'])) {
                return url(config('settings.gift_image_url_path') . $this->pic_url);
            }
            return $this->url;
        }
        return URL::to('/img/gifts/default.jpg');
    }

    public function hasPicture()
    {
        return $this->pic_url != null;
    }
    /**
     * Return a flag that indicates if the gift is disabled
     * @return bool
     */
    public function isDisabled()
    {
        return $this->status == self::STATUS_DISABLED;
    }

    /**
     * Return a flag that indicates if the gift is archived
     * @return bool
     */
    public function isArchived()
    {
        return $this->status == self::STATUS_ARCHIVED;
    }

    /*
    |--------------------------------------------------------------------------
    | Relationship Methods
    |--------------------------------------------------------------------------
    */

    /**
     * One-To-Many Relationship Method for accessing the Gift->UserGift
     *
     * @return QueryBuilder Object
     */
    public function userGifts()
    {
        return $this->hasMany('App\UserGift');
    }

    /**
     * Many-To-Many Relationship Method for accessing the gift count
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
