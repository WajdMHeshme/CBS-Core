<?php

namespace App\Notifications;

use App\Models\BookingCommission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class CommissionApprovedNotification extends Notification
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
            'title' => 'Commission Approved',
            'message' => 'Your commission payment has been approved successfully.',
            'commission_id' => $this->commission->id,
            'booking_id' => $this->commission->booking_id,
            'amount' => $this->commission->amount,
            'currency' => $this->commission->currency,
            'status' => $this->commission->status,
        ];
    }
}
