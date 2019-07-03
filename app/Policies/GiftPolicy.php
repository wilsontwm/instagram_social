<?php

namespace App\Policies;

use App\Gift;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GiftPolicy
{
    use HandlesAuthorization;

    /**
     * Return a flag whether the user can see a list of gifts
     * @param User $user
     * @return bool
     */
    public function indexAdmin(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Return a flag whether the user can create new gift
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Return a flag whether the user can update gift
     * @param User $user
     * @return bool
     */
    public function update(User $user, Gift $gift)
    {
        return $user->isAdmin();
    }
}
