<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CashoutRequest extends Model
{
    /**
     * The constant for the value representation of gender
     */
    const STATUS_PENDING = 0;
    const STATUS_WITHDRAWN = 1;
    const STATUS_APPROVED = 2;
    const STATUS_REJECTED = 3;
    const STATUS_ARRAY = [
        self::STATUS_PENDING => 'Pending',
        self::STATUS_WITHDRAWN => 'Withdrawn',
        self::STATUS_APPROVED => 'Approved',
        self::STATUS_REJECTED => 'Rejected'
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cashout_requests';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'amount', 'status', 'remarks', 'processed_at'
    ];

    /**
     * Get the date time of the request
     * @return string
     */
    public function getDateTime()
    {
        $date = Carbon::parse($this->created_at)->format('F j\\, Y');
        return $date;
    }

    /**
     * Get the processed date time of the request
     * @return string
     */
    public function getProcessedDateTime()
    {
        $date = Carbon::parse($this->processed_at)->format('F j\\, Y');
        return $date;
    }

    /**
     * A flag that indicates if the request is processed
     * @return bool
     */
    public function isProcessed()
    {
        return $this->status == self::STATUS_APPROVED || $this->status == self::STATUS_REJECTED;
    }

    /**
     * Get the status of the request
     * @return array
     */
    public function getStatus()
    {
        return self::STATUS_ARRAY[$this->status];
    }

    /**
     * Get the label of the request
     * @return array
     */
    public function getLabel()
    {
        $label = '';
        if($this->status == self::STATUS_PENDING) {
            $label = 'label-warning';
        } else if($this->status == self::STATUS_WITHDRAWN) {
            $label = 'label-info';
        } else if($this->status == self::STATUS_APPROVED) {
            $label = 'label-success';
        } else if($this->status == self::STATUS_REJECTED) {
            $label = 'label-danger';
        }

        return $label;
    }

    /** A flag that indicates if the cash out can be withdrawn
     * @return bool
     */
    public function canWithdraw()
    {
        return $this->status == self::STATUS_PENDING;
    }

    /**
     * Get the flag that determines if the cashout request is sufficient, if no, gives the insufficient cashout request
     * @return array
     */
    public function getSufficiencyResult()
    {
        $result = [];
        $result['isSufficient'] = true;
        $i = 0;

        foreach($this->cashoutRequestItems as $cashoutRequestItem)
        {
            if(!$cashoutRequestItem->isSufficient()) {
                $result['isSufficient'] = false;
                $result['insufficientGift'][$i] = $cashoutRequestItem->getTitle().' - '.$cashoutRequestItem->insufficientCount()[0];
                $i++;
            }
        }

        return $result;
    }
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

    /**
     * One-To-Many Relationship Method for accessing the CashoutRequestItems
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cashoutRequestItems()
    {
        return $this->hasMany('App\CashOutRequestItem');
    }
}
