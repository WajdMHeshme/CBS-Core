<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AmenityController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CarController;
use App\Http\Controllers\Admin\CarImageController;
use App\Http\Controllers\Admin\Reports\BookingsReportController;
use App\Http\Controllers\Admin\Reports\CarReportController;
use App\Http\Controllers\Employee\BookingMessageController;
use App\Http\Controllers\Employee\EmployeeDashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::post('/dashboard/notifications/read', function () {
    auth()->user()->unreadNotifications->markAsRead();
    return back();
})->name('notifications.read');

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->prefix('chat')->group(function () {
    Route::get('/bookings/{booking}/messages', [BookingMessageController::class, 'index']);
    Route::post('/bookings/{booking}/messages', [BookingMessageController::class, 'store']);
});

Route::view('/team', 'team')->name('team.index');

/*
|--------------------------------------------------------------------------
| Language Switch
|--------------------------------------------------------------------------
*/
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['ar', 'en'])) {
        Session::put('locale', $locale);
    }
    return redirect()->back();
});

/*
|--------------------------------------------------------------------------
| Dashboard Routes (Admin & Employee)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'check.active', 'role:admin|employee'])
    ->prefix('dashboard')
    ->name('dashboard.')
    ->group(function () {

        // Dashboard
        Route::get('/', function () {
            $user = auth()->user();

            if ($user->hasRole('admin')) {
                return (new DashboardController)->index(request());
            }

            if ($user->hasRole('employee')) {
                return (new EmployeeDashboardController)->index(request());
            }

            abort(403);
        })->name('index');

        /*
        |--------------------------------------------------------------------------
        | Admin Only
        |--------------------------------------------------------------------------
        */
        Route::middleware(['role:admin'])->group(function () {

            Route::resource('amenities', AmenityController::class)->except(['show']);

            /*
            |--------------------------------------------------------------------------
            | Cars
            |--------------------------------------------------------------------------
            */
            Route::resource('cars', CarController::class);

            Route::get('cars/types', [CarController::class, 'types'])
                ->name('cars.types');

            /*
            |--------------------------------------------------------------------------
            | Car Images
            |--------------------------------------------------------------------------
            */
            Route::prefix('cars/{car}/images')->name('cars.images.')->group(function () {
                Route::get('/', [CarImageController::class, 'index'])->name('index');
                Route::post('/', [CarImageController::class, 'store'])->name('store');
                Route::patch('{image}/main', [CarImageController::class, 'setMain'])->name('setMain');
                Route::delete('{image}', [CarImageController::class, 'destroy'])->name('destroy');
                Route::delete('{image}/force', [CarImageController::class, 'forceDestroy'])->name('forceDestroy');
                Route::get('trashed', [CarImageController::class, 'trashed'])->name('trashed');
                Route::patch('{image}/restore', [CarImageController::class, 'restore'])->name('restore');
            });

            /*
            |--------------------------------------------------------------------------
            | Reports
            |--------------------------------------------------------------------------
            */
            Route::view('reports', 'dashboard.reports.index')->name('reports.index');

            // Bookings Report
            Route::get('reports/bookings', [BookingsReportController::class, 'index'])
                ->name('reports.bookings');

            Route::get('reports/bookings/export', [BookingsReportController::class, 'export'])
                ->name('reports.bookings.export');

            // ✅ Cars Report (الجديد)
            Route::get('reports/cars', [CarReportController::class, 'index'])
                ->name('reports.cars');

            Route::get('reports/cars/export', [CarReportController::class, 'export'])
                ->name('reports.cars.export');

            /*
            |--------------------------------------------------------------------------
            | Users & Employees
            |--------------------------------------------------------------------------
            */
            Route::get('users', [AdminController::class, 'index'])->name('admin.employees.index');
            Route::get('employees/create', [AdminController::class, 'create'])->name('admin.employees.create');
            Route::post('employees', [AdminController::class, 'store'])->name('admin.employees.store');

            Route::get('users/{id}/role', [AdminController::class, 'editRole'])->name('admin.users.edit-role');
            Route::patch('users/{id}/role', [AdminController::class, 'changeRole'])->name('admin.users.change-role');

            Route::get('users/{userId}/status', [AdminController::class, 'editAccount'])->name('admin.users.edit-status');
            Route::patch('users/{userId}/status', [AdminController::class, 'toggleUserStatus'])->name('admin.users.toggle-status');

            Route::delete('users/{userId}', [AdminController::class, 'destroy'])->name('admin.users.destroy');

            Route::patch('change-password', [AdminController::class, 'changePassword'])->name('admin.change-password');
        });

        /*
        |--------------------------------------------------------------------------
        | Profile
        |--------------------------------------------------------------------------
        */
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
require __DIR__ . '/employee.php';
