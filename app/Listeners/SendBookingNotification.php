<?php

namespace App\Listeners;

use App\Events\BookingCreated;
use App\Models\User;
use App\Notifications\BookingActionNotification;


class SendBookingNotification
{
    public function handle(BookingCreated $event): void
    {
        $booking = $event->booking;

        $users = User::role(['admin','employee'])->get();

        foreach ($users as $user) {
            $user->notify(new BookingActionNotification(
                action: 'created',
                bookingId: $booking->id,
                byUser: $booking->user->name ?? 'Customer'
            ));
        }
    }
}
