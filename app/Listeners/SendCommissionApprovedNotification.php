<?php

namespace App\Listeners;

use App\Events\CommissionApproved;
use App\Notifications\CommissionApprovedNotification;

class SendCommissionApprovedNotification
{
    public function handle(CommissionApproved $event): void
    {
        $commission = $event->commission->load(['lessor']);

        if ($commission->lessor) {
            $commission->lessor->notify(
                new CommissionApprovedNotification($commission)
            );
        }
    }
}
