<?php

namespace App\Notifications;

use App\Models\BookingMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification
{
    use Queueable;

    public function __construct(
        public BookingMessage $message
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [

            'type' => 'new_message',

            'title' => 'New Message',

            'message' => 'You received a new message from support.',

            'conversation_id' => $this->message->conversation_id,

            'booking_id' => $this->message->booking_id ?? null,

            'url' => '/dashboard/chat/' .
                $this->message->conversation_id,
        ];
    }
}
