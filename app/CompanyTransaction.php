<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyTransaction extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'company_transactions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_transaction_id', 'amount', 'description'
    ];

}
