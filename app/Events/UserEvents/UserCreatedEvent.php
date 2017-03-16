<?php

namespace App\Events\UserEvents;

use App\Events\Event;
use App\Models\User;

class UserCreatedEvent extends Event
{
    /**
     * Instance of User
     *
     * @var User
     */
    public $user;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}