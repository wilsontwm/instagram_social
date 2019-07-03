<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if user can edit user details
     * @param User $user
     * @return bool
     */
    public function edit(User $user)
    {
        return $user->isSuperAdmin();
    }
}
