<?php

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReviewApprovedNotification extends Notification
{
    use Queueable;

    public Review $review;

    public function __construct(Review $review)
    {
        $this->review = $review;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type' => 'review_approved',
            'title' => 'Review Approved 🎉',
            'message' => 'Your review for car #' . $this->review->car_id . ' has been approved.',
            'review_id' => $this->review->id,
            'car_id' => $this->review->car_id,
        ];
    }
}
