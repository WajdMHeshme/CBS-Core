<?php


/*
|--------------------------------------------------------------------------
| LESSOR ROUTES
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Admin\LessorRequestAdminController;
use App\Http\Controllers\Lessor\CarController;
use App\Http\Controllers\Lessor\LessorDashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'check.active', 'role:lessor'])
    ->prefix('dashboard')
    ->name('lessor.')
    ->group(function () {

        Route::get('/lessor-cars', [LessorDashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('lessor/cars', CarController::class);
    });


/*
|--------------------------------------------------------------------------
| LESSOR REQUESTS (admin)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'check.active', 'role:admin'])
    ->prefix('dashboard')
    ->name('dashboard.')
    ->group(function () {
        Route::get('customer-lessor-requests', [LessorRequestAdminController::class, 'index'])
            ->name('lessor-requests.index');

        Route::patch('lessor-requests/{lessorRequest}/status', [LessorRequestAdminController::class, 'update'])
            ->name('lessor-requests.status');
    });
