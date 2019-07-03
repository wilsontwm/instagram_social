<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTransaction extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_transactions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'user_gift_id', 'amount', 'description', 'is_top_up'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationship Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Many-To-One Relationship Method for accessing the user
     *
     * @return QueryBuilder Object
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
