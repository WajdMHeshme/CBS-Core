<?php

namespace App\Services;

use App\Models\Car;
use App\Models\CarReview;

class CarReviewService
{
public function getCarReviews($carId)
{
    $car = Car::findOrFail($carId);

    $reviews = $car->reviews()
        ->with('user:id,name')
        ->paginate(10);

    $average = $car->reviews()->avg('rating');

    return [
        'reviews' => $reviews,
        'average' => $average,
    ];
}

    public function create($carId, $userId, $data)
    {
        $exists = CarReview::where('user_id', $userId)
            ->where('car_id', $carId)
            ->exists();

        if ($exists) {
            throw new \Exception('You already reviewed this car');
        }

        return CarReview::create([
            'user_id' => $userId,
            'car_id' => $carId,
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
        ]);
    }

    public function delete($review)
    {
        return $review->delete();
    }
}
