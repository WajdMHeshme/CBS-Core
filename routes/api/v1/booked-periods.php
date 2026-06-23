<?php

use App\Http\Controllers\Visitor\CarController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cars/{car}/booked-periods', [CarController::class, 'bookedPeriods']);
});
