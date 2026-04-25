<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Car;
use App\Models\Review;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ReviewService
{
    public function addRating(int $userId, array $data): Review
    {
        return DB::transaction(function () use ($userId, $data) {

            $booking = Booking::findOrFail($data['booking_id']);

            if ($booking->user_id !== $userId) {
                throw new AuthorizationException(
                    'You are not allowed to rate this booking'
                );
            }

            if ($booking->status !== 'completed') {
                throw new BadRequestHttpException(
                    'You cannot rate until the booking is completed'
                );
            }

            $review = Review::where('user_id', $userId)
                ->where('car_id', $booking->car_id)
                ->first();

            if ($review) {

                if (isset($data['rating'])) {
                    throw new BadRequestHttpException(
                        'Rating cannot be modified'
                    );
                }

                $review->update([
                    'comment' => $data['comment'],
                ]);

                return $review;
            }

            if (! isset($data['rating'])) {
                throw new BadRequestHttpException(
                    'Rating is required for first review'
                );
            }

            $review = Review::create([
                'booking_id' => $booking->id,
                'user_id' => $userId,
                'car_id' => $booking->car_id,
                'rating' => $data['rating'],
                'comment' => $data['comment'] ?? null,
            ]);

            $car = Car::findOrFail($booking->car_id);

            $car->increment('rating_count');
            $car->increment('rating_sum', $data['rating']);

            $car->update([
                'rating_avg' => round(
                    $car->rating_sum / $car->rating_count,
                    2
                ),
            ]);

            return $review;
        });
    }

    public function toggleUserStatus(int $userId, bool $status): User
    {
        $user = User::findOrFail($userId);

        $user->update(['is_active' => $status]);

        return $user;
    }
}
