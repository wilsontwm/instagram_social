<?php
namespace App\Services;

use App\CompanyTransaction;
use App\Gift;
use App\User;
use App\UserGift;
use App\UserTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    /**
     * Create the gift when user send gift to another
     * @param Gift $gift
     * @param User $recipient
     * @param string $message
     */
    public static function sendGift(Gift $gift, User $recipient, $message = '')
    {
        $price = $gift->price;

        // Deduct from the user credit balance
        $sender = Auth::user();
        $sender->credit_balance = $sender->credit_balance - $price;
        $sender->save();

        $userGift = UserGift::create([
            'user_id'       => $recipient->id,
            'sender_id'     => Auth::id(),
            'gift_id'       => $gift->id,
            'message'       => $message == '' ? null : $message,
            'price'         => $price
        ]);

        // Add gift to user's gift count
        self::updateGiftCount($recipient, $gift, 1);

        $description = Auth::user()->name.' sent '.$gift->title.' to '.$recipient->name.' at the price of '.$price;
        // Record the transactions
        self::recordTransaction($price, $description, $userGift->id, false);

        return $userGift;
    }

    /**
     * Record the transactions made by the user
     * @param $price
     * @param string $description
     * @param null $userGiftId
     * @param bool $isTopUp
     */
    public static function recordTransaction($price, $description = '', $userGiftId = null, $isTopUp = false)
    {
        $userTransaction = UserTransaction::create([
            'user_id'       => Auth::id(),
            'user_gift_id'  => $userGiftId,
            'amount'        => -$price,
            'description'   => $description,
            'is_top_up'     => $isTopUp
        ]);

        CompanyTransaction::create([
            'user_transaction_id'   => $userTransaction->id,
            'amount'                => $price,
            'description'           => $description
        ]);
    }

    /**
     * Update the gift count of the user
     * @param User $user
     * @param Gift $gift
     * @param int $amount
     */
    public static function updateGiftCount(User $user, Gift $gift, $amount = 0)
    {
        if($gift_count = $user->gifts->where('id', $gift->id)->first()){
            $gift_count->pivot->count = $gift_count->pivot->count + $amount;
            $gift_count->pivot->save();
        } else {
            $user->gifts()->attach($gift->id, ['count' => $amount]);
        }
    }
}

?>