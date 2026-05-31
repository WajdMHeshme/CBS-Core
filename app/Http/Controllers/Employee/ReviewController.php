<?php

namespace App\Http\Controllers\Employee;

use App\Events\ReviewApproved;
use App\Http\Controllers\Controller;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['user', 'car'])
            ->latest()
            ->paginate(20);

        return view('dashboard.employee.reviews.index', compact('reviews'));
    }

    public function show(Review $review)
    {
        $review->load(['user', 'car']);

        return view('dashboard.employee.reviews.show', compact('review'));
    }

public function approve(Review $review)
{
    $review->update([
        'status' => Review::STATUS_APPROVED
    ]);

    event(new ReviewApproved($review));

    return back()->with('success', 'Review approved');
}

    public function reject(Review $review)
    {
        $review->update([
            'status' => Review::STATUS_REJECTED
        ]);

        return back()->with('success', 'Review rejected');
    }
}
