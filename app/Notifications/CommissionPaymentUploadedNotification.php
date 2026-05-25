<?php

namespace App\Notifications;

use App\Models\BookingCommission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CommissionPaymentUploadedNotification extends Notification
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
            'title' => 'Payment Uploaded',

            'message' =>
                'Payment proof has been uploaded successfully.',

            'commission_id' => $this->commission->id,

            'booking_id' => $this->commission->booking_id,

            'payment_reference' =>
                $this->commission->payment_reference,

            'status' => $this->commission->status,
        ];
    }
}
