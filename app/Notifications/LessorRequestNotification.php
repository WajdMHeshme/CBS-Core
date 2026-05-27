<?php

namespace App\Notifications;

use App\Models\LessorRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LessorRequestNotification extends Notification
{
    use Queueable;

    public function __construct(
        public LessorRequest $request
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'New Lessor Request',

            'message' => sprintf(
                'A new lessor request has been submitted by %s.',
                $this->request->user->name ?? 'Unknown User'
            ),

            'request_id' => $this->request->id,
            'status'     => $this->request->status ?? 'pending',
        ];
    }
}
