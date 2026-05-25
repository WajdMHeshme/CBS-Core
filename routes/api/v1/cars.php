<?php

use App\Http\Controllers\Customer\FavoriteController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])
    ->prefix('favorites')
    ->group(function () {

        Route::post('/', [FavoriteController::class, 'store'])
            ->name('customer.favorites.store');

        Route::delete('/', [FavoriteController::class, 'destroy'])
            ->name('customer.favorites.destroy');

        Route::get('/', [FavoriteController::class, 'index'])
            ->name('customer.favorites.index');
    });
