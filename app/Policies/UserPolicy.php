<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{

    /**
     * Intercept checks
     *
     * @param User $currentUser
     * @return bool
     */
    public function before(User $currentUser)
    {
        if ($currentUser->tokenCan('admin')) {
            return true;
        }
    }

    /**
     * Determine if a given user has permission to show
     *
     * @param User $currentUser
     * @param User $user
     * @return bool
     */
    public function show(User $currentUser,  User $user)
    {
        return $currentUser->id === $user->id;
    }

    /**
     * Determine if a given user can update
     *
     * @param User $currentUser
     * @param User $user
     * @return bool
     */
    public function update(User $currentUser, User $user)
    {
        return $currentUser->id === $user->id;
    }

    /**
     * Determine if a given user can delete
     *
     * @param User $currentUser
     * @param User $user
     * @return bool
     */
    public function destroy(User $currentUser, User $user)
    {
        return $currentUser->id === $user->id;
    }
}