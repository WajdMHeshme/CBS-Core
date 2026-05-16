<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Employee\BookingMessageController;
use App\Http\Controllers\Customer\BookingController as CustomerBookingController;

/*
|--------------------------------------------------------------------------
| Booking Messages (Employee/Admin)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum', 'check.active'])
    ->prefix('bookings')
    ->group(function () {

        Route::get('/{booking}/messages', [BookingMessageController::class, 'index']);
        Route::post('/{booking}/messages', [BookingMessageController::class, 'store']);
    });

/*
|--------------------------------------------------------------------------
| Customer Bookings
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'check.active', 'role:customer'])
    ->prefix('bookings')
    ->group(function () {

        Route::get('/', [CustomerBookingController::class, 'index']);

        Route::post('/', [CustomerBookingController::class, 'store']);

        Route::get('/{booking}', [CustomerBookingController::class, 'show']);

        Route::delete('/{booking}', [CustomerBookingController::class, 'cancel']);
    });
