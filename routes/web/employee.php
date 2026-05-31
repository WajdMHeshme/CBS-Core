<?php

use App\Http\Controllers\Employee\CommissionController;
use App\Http\Controllers\Employee\EmployeeBookingController;

use App\Http\Controllers\Employee\EmployeeDashboardController;
use App\Http\Controllers\Employee\ReviewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| EMPLOYEE ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin|employee'])
    ->prefix('dashboard')
    ->name('employee.')
    ->group(function () {

        Route::get('/employee', [EmployeeDashboardController::class, 'index'])
            ->name('dashboard.employee');

        // Bookings List employee
        Route::get('/my-own-bookings', [EmployeeBookingController::class, 'myBookings'])
            ->name('bookings.my');

        // Bookings List admin
        Route::get('/bookings', [EmployeeBookingController::class, 'index'])
            ->name('bookings.index');
        // Pending Bookings
        Route::get('/bookings/pending', [EmployeeBookingController::class, 'pending'])
            ->name('bookings.pending');

        Route::get('/bookings/{booking}', [EmployeeBookingController::class, 'show'])->name('bookings.show');

        // Actions
        // reschedual
        Route::get(
            '/bookings/{booking}/reschedule',
            [EmployeeBookingController::class, 'rescheduleForm']
        )->name('reschedule.form');
        // apprve
        Route::patch('/bookings/{booking}/approve', [EmployeeBookingController::class, 'approve'])
            ->name('bookings.approve');
        // cancel
        Route::patch('/bookings/{booking}/cancel', [EmployeeBookingController::class, 'cancel'])
            ->name('bookings.cancel');

        Route::patch('/bookings/{booking}/reschedule', [EmployeeBookingController::class, 'reschedule'])
            ->name('bookings.reschedule');

        Route::patch('/bookings/{booking}/complete', [EmployeeBookingController::class, 'complete'])
            ->name('bookings.complete');

        Route::patch('/bookings/{booking}/reject', [EmployeeBookingController::class, 'reject'])
            ->name('bookings.reject');

        // Booking Details
        Route::get('/bookings/{booking}', [EmployeeBookingController::class, 'show'])
            ->name('bookings.show');
    });

Route::prefix('employee/commissions')->middleware(['auth'])->group(function () {

    Route::post('/request/{booking}', [CommissionController::class, 'requestCommission'])
        ->name('employee.commissions.request');

    Route::post('/approve/{commission}', [CommissionController::class, 'approve'])
        ->name('employee.commissions.approve');

    Route::post('/reject/{commission}', [CommissionController::class, 'reject'])
        ->name('employee.commissions.reject');
});

Route::post(
    '/employee/commissions/approve/{commission}',
    [CommissionController::class, 'approve']
)->middleware(['auth'])
    ->name('employee.commissions.approve');

Route::post(
    '/employee/bookings/{booking}/conversation',
    [\App\Http\Controllers\Employee\BookingConversationController::class, 'send']
)->name('employee.booking.conversation');

// =========================
// Reviews Management (Employee)
// =========================
Route::middleware(['auth', 'role:admin|employee'])->group(function () {

    // 📋 All reviews
    Route::get('/reviews', [ReviewController::class, 'index'])
        ->name('employee.reviews.index');

    // ⏳ Pending reviews
    Route::get('/reviews/pending', [ReviewController::class, 'pending'])
        ->name('employee.reviews.pending');

    // 👁️ Show review
    Route::get('/reviews/{review}', [ReviewController::class, 'show'])
        ->name('employee.reviews.show');

    // ✅ Approve review
    Route::patch('/reviews/{review}/approve', [ReviewController::class, 'approve'])
        ->name('employee.reviews.approve');

    // ❌ Reject review
    Route::patch('/reviews/{review}/reject', [ReviewController::class, 'reject'])
        ->name('employee.reviews.reject');
});
