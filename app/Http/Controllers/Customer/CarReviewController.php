<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCarReviewRequest;
use App\Models\CarReview;
use App\Services\CarReviewService;
use App\Http\Resources\CarReviewResource;
class CarReviewController extends Controller
{
    public function __construct(
        private CarReviewService $service
    ) {}

    // GET /cars/{car}/reviews
    public function index($carId)
    {
        $data = $this->service->getCarReviews($carId);

        return response()->json([
            'average_rating' => round($data['average'], 1),
            'reviews' => CarReviewResource::collection($data['reviews']),
        ]);
    }

    // POST /cars/{car}/reviews
    public function store(StoreCarReviewRequest $request, $carId)
    {
        try {
            $review = $this->service->create(
                $carId,
                auth()->id(),
                $request->validated()
            );

            return response()->json($review, 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    // DELETE /reviews/{review}
    public function destroy(CarReview $review)
    {
        if ($review->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $this->service->delete($review);

        return response()->json(['message' => 'Deleted']);
    }
}
