<?php

namespace App\Services;

use App\Events\MessageSent;
use App\Models\Booking;
use App\Models\BookingMessage;
use Illuminate\Auth\Access\AuthorizationException;

class BookingCommunicationService
{
    /**
     * Send a new message within a booking.
     */
    public function sendMessage(
        Booking $booking,
        int $senderId,
        string $message
    ): BookingMessage {

        $ownerId = (int) $booking->user_id;
        $assignedEmployeeId = (int) $booking->employee_id;

        if ($senderId !== $ownerId && $senderId !== $assignedEmployeeId) {
            throw new AuthorizationException(
                'Access Denied: You are not authorized to send messages for this booking.'
            );
        }

        $bookingMessage = $booking->messages()->create([
            'sender_id' => $senderId,
            'message'   => $message,
        ]);

        MessageSent::dispatch($bookingMessage);

        return $bookingMessage;
    }


    /**
     * Get booking messages (chat history).
     */
    public function getMessages(
        Booking $booking,
        int $userId
    ) {

        $ownerId = (int) $booking->user_id;
        $assignedEmployeeId = (int) $booking->employee_id;

        // Authorization check
        if ($userId !== $ownerId && $userId !== $assignedEmployeeId) {
            throw new AuthorizationException(
                'Access Denied: You are not authorized to view these messages.'
            );
        }

        return $booking->messages()
            ->with('sender:id,name')
            ->orderBy('created_at', 'asc')
            ->get();
    }
}
