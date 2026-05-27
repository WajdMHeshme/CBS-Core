<?php
namespace App\Listeners;

use App\Events\CommissionPaymentUploaded;
use App\Notifications\CommissionPaymentUploadedNotification;

class SendPaymentUploadedNotification
{
    public function handle(CommissionPaymentUploaded $event): void
    {
        $commission = $event->commission->load('employee');

        $commission->employee?->notify(
            new CommissionPaymentUploadedNotification($commission)
        );
    }
}
