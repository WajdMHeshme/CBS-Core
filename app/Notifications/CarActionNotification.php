<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CarActionNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $action,     // created | updated | deleted
        public string $carTitle,   // car name/title
        public string $byUser      // user name
    ) {}

    /**
     * Channels
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Data stored in notifications table
     */
    public function toDatabase($notifiable): array
    {
        return [
            'message' => "Car '{$this->carTitle}' {$this->action}",
            'by'      => $this->byUser,
            'type'    => 'car',
        ];
    }
}
