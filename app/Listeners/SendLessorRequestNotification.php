<?php

namespace App\Listeners;

use App\Events\LessorRequestCreated;
use App\Models\User;
use App\Notifications\LessorRequestNotification;

class SendLessorRequestNotification
{
    public function handle(LessorRequestCreated $event): void
    {
        $request = $event->lessorRequest->load('user');

        $admins = User::role('admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(
                new LessorRequestNotification($request)
            );
        }
    }
}
