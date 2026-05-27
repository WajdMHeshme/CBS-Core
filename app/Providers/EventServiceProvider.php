<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [

        \App\Events\BookingCreated::class => [
            \App\Listeners\SendBookingNotification::class,
        ],

        \App\Events\CommissionRequested::class => [
            \App\Listeners\SendCommissionRequestedNotification::class,
        ],

        \App\Events\CommissionPaymentUploaded::class => [
            \App\Listeners\SendPaymentUploadedNotification::class,
        ],

        \App\Events\CommissionApproved::class => [
            \App\Listeners\GeneratePDFOnApproval::class,
            \App\Listeners\SendCommissionApprovedNotification::class,
        ],

        \App\Events\CommissionRejected::class => [
            \App\Listeners\SendCommissionRejectedNotification::class,
        ],
    ];
}
