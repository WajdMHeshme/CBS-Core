<?php

use App\Http\Controllers\Customer\FavoriteController;
use Illuminate\Support\Facades\Route;

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
