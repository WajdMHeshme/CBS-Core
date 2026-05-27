<?php

namespace App\Listeners;

use App\Events\CommissionRejected;
use App\Notifications\CommissionRejectedNotification;

class SendCommissionRejectedNotification
{
    public function handle(CommissionRejected $event): void
    {
        $commission = $event->commission->fresh(['lessor']);

        if (! $commission?->lessor) {
            return;
        }

        $commission->lessor->notify(
            new CommissionRejectedNotification(
                $commission,
                $event->notes
            )
        );
    }
}
