<?php

namespace App\Listeners;

use App\Events\MessageSent;
use App\Models\User;
use App\Notifications\NewMessageNotification;

class SendNewMessageNotification
{
    public function handle(MessageSent $event): void
    {
        $message = $event->message;
        $booking = $message->booking;

        if (!$booking) {
            return;
        }

        $senderId = (int) $message->sender_id;

        $receiverId = ((int) $booking->user_id === $senderId)
            ? (int) $booking->employee_id
            : (int) $booking->user_id;

        if (!$receiverId) {
            return;
        }

        $receiver = User::find($receiverId);

        if (!$receiver) {
            return;
        }

        $receiver->notify(
            new NewMessageNotification($message)
        );
    }
}
