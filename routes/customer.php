<?php

use App\Http\Controllers\Api\LessorRequestApiController;
use App\Http\Controllers\Customer\BookingController as CustomerBookingController;
use App\Http\Controllers\Customer\CarReviewController;
use App\Http\Controllers\Customer\FavoriteController;
use App\Http\Controllers\Customer\LessorRequestController;
use App\Http\Controllers\Customer\ReviewController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'role:customer'])->group(function () {
    // booking reviews
    Route::post('/ratings', [ReviewController::class, 'store'])
        ->name('customer.ratings.store');

    //car reviews
    Route::get('/cars/{car}/reviews', [CarReviewController::class, 'index'])
        ->name('customer.cars.reviews.index');
    Route::post('/cars/{car}/reviews', [CarReviewController::class, 'store'])
        ->name('customer.cars.reviews.store');
    Route::delete('/reviews/{review}', [CarReviewController::class, 'destroy'])
        ->name('customer.reviews.destroy');
});

Route::middleware(['auth:sanctum'])->prefix('bookings')->group(function () {
    // display user bookings
    Route::get('/', [CustomerBookingController::class, 'index']);
    // user add a new booking
    Route::post('/', [CustomerBookingController::class, 'store']);
    // display spesfic booking
    Route::get('/{booking}', [CustomerBookingController::class, 'show']);
    // cancel the booking befor appointment
    Route::delete('/{booking}', [CustomerBookingController::class, 'cancel']);
});


Route::middleware(['auth:sanctum', 'role:customer'])
    ->prefix('favorites')
    ->group(function () {

        Route::post('/{car}', [FavoriteController::class, 'store'])
            ->name('customer.favorites.store');

        Route::delete('/{car}', [FavoriteController::class, 'destroy'])
            ->name('customer.favorites.destroy');

        Route::get('/', [FavoriteController::class, 'index'])
            ->name('customer.favorites.index');
    });
