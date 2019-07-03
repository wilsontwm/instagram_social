<?php

namespace App\Policies;

use App\CashoutRequest;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CashoutRequestPolicy
{
    use HandlesAuthorization;

    /**
     * Return a flag whether the user can see the cashout request detail
     * @param User $user
     * @param CashoutRequest $cashoutRequest
     * @return bool
     */
    public function view(User $user, CashoutRequest $cashoutRequest)
    {
        return $cashoutRequest->user->id == $user->id;
    }

    /**
     * Return a flag whether the user can withdraw the cashout request
     * @param User $user
     * @param CashoutRequest $cashoutRequest
     * @return bool
     */
    public function withdraw(User $user, CashoutRequest $cashoutRequest)
    {
        return $cashoutRequest->user->id == $user->id;
    }

    /**
     * Return a flag whether the user can see all the cashout request in cashout management
     * @param User $user
     * @return bool
     */
    public function admin(User $user)
    {
        return $user->isAdmin();
    }
}
