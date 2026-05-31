<?php

namespace App\Services;

use Illuminate\Validation\ValidationException;
use App\Models\Review;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ReviewService
{
public function addRating(int $userId, int $carId, array $data): Review
{
    return DB::transaction(function () use ($userId, $carId, $data) {

        $exists = Review::where('car_id', $carId)
            ->where('user_id', $userId)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'review' => 'You have already submitted a review for this car.'
            ]);
        }

        if (!isset($data['rating'])) {
            throw new BadRequestHttpException(
                'Rating is required to submit a review.'
            );
        }

        return Review::create([
            'user_id' => $userId,
            'car_id' => $carId,
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
            'status' => Review::STATUS_PENDING,
        ]);
    });
}
}
