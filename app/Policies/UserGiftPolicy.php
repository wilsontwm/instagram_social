<?php

namespace App\Policies;

use App\User;
use App\UserGift;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserGiftPolicy
{
    use HandlesAuthorization;

    /**
     * Return a flag whether the user can create new gift
     * @param User $user
     * @return bool
     */
    public function show(User $user, UserGift $gift)
    {
        return ( $gift->user && $gift->user->id == $user->id )
                || ( $gift->sender && $gift->sender->id == $user->id );
    }
}
