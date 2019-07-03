<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CashoutRequestItem extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cashout_request_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cashout_request_id', 'gift_id', 'quantity'
    ];

    public $timestamps = false;

    /**
     * Returns the URL of the gift picture, default if none.
     *
     * @return mixed
     */
    public function getPicUrl()
    {
        return $this->gift->getPicUrl();
    }

    /**
     * Returns the title of the gift
     *
     * @return mixed
     */
    public function getTitle()
    {
        return $this->gift->title;
    }

    /**
     * Returns the cashout price of the gift
     * @return mixed
     */
    public function getCashoutPrice()
    {
        $rate = config('settings.cashout_conversion_rate');
        return $this->gift->price * $rate;
    }

    /**
     * Returns the total cashout price of the gift
     * @return mixed
     */
    public function getTotalCashoutPrice()
    {
        return $this->getCashoutPrice() * $this->quantity;
    }

    /**
     * Returns a flag that indicate if the cashout exceed gift count
     * @return bool
     */
    public function isSufficient()
    {
        $userGiftQty = 0;
        $userGift = $this->cashoutRequest->user->gifts()->where('id', $this->gift->id)->first();

        if($userGift) {
            $userGiftQty = $userGift->pivot->count;
        }

        return $this->cashoutRequest->isProcessed() || $this->quantity <= $userGiftQty;
    }

    /**
     * Return the counter of insufficient gift count
     * @return array
     */
    public function insufficientCount()
    {
        $result = [];
        if(!$this->isSufficient()) {
            $result[0] = 0;
            $userGift = $this->cashoutRequest->user->gifts()->where('id', $this->gift->id)->first();

            if($userGift) {
                $result[0] = $userGift->pivot->count;
            }

            $result[1] = $this->quantity;
        }

        return $result;
    }
    /*
    |--------------------------------------------------------------------------
    | Relationship Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Many-To-One Relationship Method for accessing the cashout request
     *
     * @return QueryBuilder Object
     */
    public function cashoutRequest()
    {
        return $this->belongsTo('App\CashoutRequest');
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

}
