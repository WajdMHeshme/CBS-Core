<?php

namespace App\Notifications;

use App\Models\BookingCommission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CommissionRejectedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public BookingCommission $commission,
        public ?string $notes = null
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'Commission Request Rejected',

            'message' => sprintf(
                'Commission request #%s for booking #%s has been rejected. Reason: The submitted documents are incorrect or incomplete. Please re-upload the required documents or contact support for assistance.',
                $this->commission->id,
                $this->commission->booking_id
            ),

            'commission_id' => $this->commission->id,
            'booking_id'    => $this->commission->booking_id,
            'status'        => $this->commission->status,

            // optional for debugging/admin use only
            'notes'         => $this->notes,
        ];
    }
}
