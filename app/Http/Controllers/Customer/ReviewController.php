<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use App\Services\ReviewService;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function __construct(
        protected ReviewService $reviewService
    ) {}

    public function index($carId)
    {
        $reviews = Review::where('car_id', $carId)
            ->where('status', Review::STATUS_APPROVED)
            ->with('user:id,name')
            ->get()
            ->makeHidden(['user_id']);

        return response()->json([
            'reviews' => $reviews
        ]);
    }

    public function store(StoreReviewRequest $request, int $car)
    {
        $data = $request->validated();

        $review = $this->reviewService->addRating(
            Auth::id(),
            $car,
            $data
        );

        $review->load(['user', 'car']);

        return response()->json([
            'message' => 'Your review has been submitted and is awaiting approval.',
            'review' => new ReviewResource($review),
        ], 201);
    }
}
