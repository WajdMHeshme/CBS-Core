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
        $sender  = $this->message->sender;
        $booking = $this->message->booking;

        $senderName  = $sender?->name ?? 'Someone';
        $bookingId   = $this->message->booking_id;
        $carInfo     = $booking?->car?->name ?? "Booking #{$bookingId}";
        $preview     = $this->getMessagePreview();

        return [
            'type'       => 'new_message',
            'title'      => 'New Message from ' . $senderName,
            'message'    => "\"{$preview}\" — regarding {$carInfo}",
            'booking_id' => $bookingId,
            'sender_id'  => $this->message->sender_id,
            'sender_name' => $senderName,
            'url'        => '/dashboard/bookings/' . $bookingId . '/chat',
        ];
    }

    /**
     * Truncate the message to a short readable preview.
     */
    private function getMessagePreview(int $limit = 60): string
    {
        $text = $this->message->message ?? '';

        return strlen($text) > $limit
            ? substr($text, 0, $limit) . '...'
            : $text;
    }
}
