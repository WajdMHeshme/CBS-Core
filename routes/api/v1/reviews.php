<?php

use App\Http\Controllers\Customer\CarReviewController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\ReviewController;

Route::middleware(['auth:sanctum'])
    ->group(function () {

        Route::post('/reviews', [ReviewController::class, 'store']);

        //car reviews
        Route::get('/cars/{car}/reviews', [CarReviewController::class, 'index'])
            ->name('customer.cars.reviews.index');
        Route::post('/cars/{car}/reviews', [CarReviewController::class, 'store'])
            ->name('customer.cars.reviews.store');
        Route::delete('/reviews/{review}', [CarReviewController::class, 'destroy'])
            ->name('customer.reviews.destroy');
    });


Route::middleware(['auth:sanctum', 'role:customer'])->group(function () {
    // booking reviews
    Route::post('/ratings', [ReviewController::class, 'store'])
        ->name('customer.ratings.store');
});
