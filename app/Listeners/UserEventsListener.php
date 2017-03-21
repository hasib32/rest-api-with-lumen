<?php

namespace App\Listeners;

use Illuminate\Events\Dispatcher;
use App\Events\UserEvents\UserCreatedEvent;
use App\Mails\WelcomeEmail;
use Illuminate\Support\Facades\Mail;

class UserEventsListener
{
    /**
     * Handle the user created event
     *
     * @param UserCreatedEvent $event
     */
    public function onUserCreatedEvent($event)
    {
        $user = $event->user;

        //send welcome email to the user
        Mail::to($user)->send(new WelcomeEmail($user));
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            UserCreatedEvent::class,
            'App\Listeners\UserEventsListener@onUserCreatedEvent'
        );
    }
}