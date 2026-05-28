<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LessorRequestStatusNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $status
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [

            'type' => 'lessor_request_status',

            'title' => 'Lessor Request Update',

            'message' => $this->status === 'approved'
                ? 'Your lessor request has been approved.'
                : 'Your lessor request has been rejected.',

            'status' => $this->status,

            'url' => $this->status === 'approved'
                ? '/lessor/dashboard'
                : '/dashboard',
        ];
    }
}
