<?php

namespace App\Notifications;

use App\Models\BookingCommission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CommissionRequestedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public BookingCommission $commission
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'Commission Payment Requested',

            'message' => sprintf(
                'A new commission payment request has been created for booking #%s. Amount required: %s %s',
                $this->commission->booking_id,
                number_format($this->commission->amount, 2),
                $this->commission->currency
            ),

            'commission_id' => $this->commission->id,
            'booking_id' => $this->commission->booking_id,
            'amount' => (float) $this->commission->amount,
            'currency' => $this->commission->currency,
            'status' => $this->commission->status,
        ];
    }
}
