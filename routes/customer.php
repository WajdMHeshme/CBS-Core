<?php

use App\Http\Controllers\Customer\BookingController as CustomerBookingController;
use App\Http\Controllers\Customer\FavoriteController;
use App\Http\Controllers\Customer\ReviewController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'role:customer'])->group(function () {
    Route::post('/ratings', [ReviewController::class, 'store'])
        ->name('customer.ratings.store');
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
