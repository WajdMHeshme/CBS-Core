<?php

namespace App\Listeners;

use App\Events\ReviewApproved;
use App\Notifications\ReviewApprovedNotification;

class SendReviewApprovedNotification
{
    public function handle(ReviewApproved $event)
    {
        $review = $event->review;

        $review->user->notify(
            new ReviewApprovedNotification($review)
        );
    }
}
