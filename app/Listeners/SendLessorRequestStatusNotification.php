<?php

namespace App\Listeners;

use App\Events\LessorRequestStatusUpdated;
use App\Notifications\LessorRequestStatusNotification;

class SendLessorRequestStatusNotification
{
    public function handle(
        LessorRequestStatusUpdated $event
    ): void {

        $event->lessorRequest
            ->user
            ->notify(
                new LessorRequestStatusNotification(
                    $event->status
                )
            );
    }
}
