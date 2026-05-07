<?php

use App\Http\Controllers\Employee\EmployeeBookingController;
use App\Http\Controllers\Employee\EmployeeDashboardController;
use App\Http\Controllers\Lessor\CarController;
use App\Http\Controllers\Lessor\LessorDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| EMPLOYEE ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'check.active', 'role:employee'])
    ->prefix('dashboard')
    ->name('employee.')
    ->group(function () {

        Route::get('/', [EmployeeDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('bookings', [EmployeeBookingController::class, 'index'])
            ->name('bookings.index');

        Route::get('bookings/my', [EmployeeBookingController::class, 'myBookings'])
            ->name('bookings.my');

        Route::get('bookings/pending', [EmployeeBookingController::class, 'pending'])
            ->name('bookings.pending');

        Route::get('bookings/{booking}', [EmployeeBookingController::class, 'show'])
            ->name('bookings.show');

        Route::get('bookings/{booking}/reschedule', [EmployeeBookingController::class, 'rescheduleForm'])
            ->name('reschedule.form');

        Route::patch('bookings/{booking}/approve', [EmployeeBookingController::class, 'approve'])
            ->name('bookings.approve');

        Route::patch('bookings/{booking}/cancel', [EmployeeBookingController::class, 'cancel'])
            ->name('bookings.cancel');

        Route::patch('bookings/{booking}/reschedule', [EmployeeBookingController::class, 'reschedule'])
            ->name('bookings.reschedule');

        Route::patch('bookings/{booking}/complete', [EmployeeBookingController::class, 'complete'])
            ->name('bookings.complete');

        Route::patch('bookings/{booking}/reject', [EmployeeBookingController::class, 'reject'])
            ->name('bookings.reject');
    });

/*
|--------------------------------------------------------------------------
| LESSOR ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'check.active', 'role:lessor'])
    ->prefix('dashboard')
    ->name('lessor.')
    ->group(function () {

        Route::get('/', [LessorDashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('cars', CarController::class);
    });
