<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\ReviewController;

Route::middleware(['auth:sanctum'])
    ->name('customer.')
    ->group(function () {
        Route::post('/cars/{car}/reviews', [ReviewController::class, 'store'])
            ->name('reviews.store');
        Route::get('/cars/{car}/reviews', [ReviewController::class, 'index']);
    });


Route::middleware(['auth:sanctum'])->group(function () {
    // booking reviews
    Route::post('/ratings', [ReviewController::class, 'store'])
        ->name('customer.ratings.store');
});
