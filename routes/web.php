<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AmenityController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CarController as AdminCarController;
use App\Http\Controllers\Admin\CarImageController;
use App\Http\Controllers\Admin\ProRequestController;
use App\Http\Controllers\Admin\Reports\BookingsReportController;
use App\Http\Controllers\Admin\Reports\CarReportController;
use App\Http\Controllers\Employee\BookingMessageController;
use App\Http\Controllers\Customer\SupportTicketController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => view('welcome'));

Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['ar', 'en'])) {
        Session::put('locale', $locale);
    }

    return redirect()->back();
});

Route::middleware(['auth'])->prefix('chat')->group(function () {
    Route::get('/bookings/{booking}/messages', [BookingMessageController::class, 'index']);
    Route::post('/bookings/{booking}/messages', [BookingMessageController::class, 'store']);
});

Route::post('/dashboard/notifications/read', function () {
    auth()->user()->unreadNotifications->markAsRead();
    return back();
})->name('notifications.read');

/*
|--------------------------------------------------------------------------
| Dashboard Redirect
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {

    if (!auth()->check()) {
        return redirect('/login');
    }

    $user = auth()->user();

    return match (true) {
        $user->hasRole('admin') => redirect()->route('dashboard.admin.index'),
        $user->hasRole('employee') => redirect()->route('employee.dashboard.employee'),
        $user->hasRole('lessor') => redirect()->route('lessor.dashboard'),
        default => redirect('/login'),
    };
})->middleware(['auth', 'check.active']);


/*
|--------------------------------------------------------------------------
| ADMIN DASHBOARD
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'check.active', 'role:admin'])
    ->prefix('dashboard')
    ->name('dashboard.admin.')
    ->group(function () {

        Route::get('/', [DashboardController::class, 'index'])->name('index');
        Route::get('/cars/pending', [AdminCarController::class, 'pending'])
            ->name('cars.pending');
        Route::resource('amenities', AmenityController::class)->except(['show']);
        Route::resource('cars', AdminCarController::class);
        Route::patch('cars/{car}/approve', [AdminCarController::class, 'approve'])
            ->name('cars.approve');

        Route::patch('cars/{car}/reject', [AdminCarController::class, 'reject'])
            ->name('cars.reject');



        Route::prefix('cars/{car}/images')->name('cars.images.')->group(function () {
            Route::get('/', [CarImageController::class, 'index'])->name('index');
            Route::post('/', [CarImageController::class, 'store'])->name('store');
            Route::patch('{image}/main', [CarImageController::class, 'setMain'])->name('setMain');
            Route::delete('{image}', [CarImageController::class, 'destroy'])->name('destroy');
            Route::delete('{image}/force', [CarImageController::class, 'forceDestroy'])->name('forceDestroy');
            Route::get('trashed', [CarImageController::class, 'trashed'])->name('trashed');
            Route::patch('{image}/restore', [CarImageController::class, 'restore'])->name('restore');
        });



        Route::get('reports/bookings', [BookingsReportController::class, 'index'])->name('reports.bookings');
        Route::get('reports/cars', [CarReportController::class, 'index'])->name('reports.cars');
        Route::get('reports/cars/export', [CarReportController::class, 'export'])
            ->name('reports.cars.export');

        Route::get('reports/bookings/export', [BookingsReportController::class, 'export'])
            ->name('reports.bookings.export');

        Route::prefix('users')->name('users.')->group(function () {
            Route::get('users', [AdminController::class, 'index'])
                ->name('index');

            Route::get('users/create', [AdminController::class, 'create'])
                ->name('create');

            Route::post('users', [AdminController::class, 'store'])
                ->name('store');

            Route::get('users/{user}/edit-role', [AdminController::class, 'editRole'])
                ->name('edit-role');

            Route::patch('users/{user}/change-role', [AdminController::class, 'changeRole'])
                ->name('change-role');

            Route::get('users/{user}/edit-status', [AdminController::class, 'editStatus'])
                ->name('edit-status');

            Route::patch('users/{user}/toggle-status', [AdminController::class, 'toggleStatus'])
                ->name('toggle-status');

            Route::delete('users/{user}', [AdminController::class, 'destroy'])
                ->name('destroy');
        });
    });



Route::middleware(['auth', 'role:employee|admin'])
    ->prefix('dashboard')
    ->group(function () {

        Route::get(
            'support-tickets',
            [SupportTicketController::class, 'index']
        )->name('support.index');

        Route::get('/dashboard/support-tickets/{ticket}', [SupportTicketController::class, 'show'])
            ->name('support.show');
    });

Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        Route::get('/pro-requests', [ProRequestController::class, 'index']);

        Route::post('/pro-requests/{proRequest}/approve', [ProRequestController::class, 'approve']);

        Route::post('/pro-requests/{proRequest}/reject', [ProRequestController::class, 'reject']);
    });

require __DIR__ . '/web/auth.php';
require __DIR__ . '/web/lessor.php';
require __DIR__ . '/web/employee.php';
