<?php

namespace App\Listeners;

use App\Notifications\ThanksVerifiedNotification;
use Illuminate\Auth\Events\Verified;

class VerifiedEmailNotification
{
    /**
     * Handle the event.
     *
     * @param Verified $event
     * @return void
     */
    public function handle(Verified $event): void
    {
        $event->user->notify(new ThanksVerifiedNotification());
    }
}
