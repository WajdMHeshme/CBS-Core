<?php

namespace App\Listeners;

use App\Events\CommissionRequested;
use App\Notifications\CommissionRequestedNotification;

class SendCommissionRequestedNotification
{
    public function handle(CommissionRequested $event): void
    {
        $commission = $event->commission->load('lessor');

        $commission->lessor?->notify(
            new CommissionRequestedNotification($commission)
        );
    }
}
