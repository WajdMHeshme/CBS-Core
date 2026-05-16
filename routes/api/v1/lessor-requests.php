<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\LessorRequestController;

Route::middleware(['auth:sanctum'])
    ->group(function () {

        Route::post('/lessor-requests', [LessorRequestController::class, 'store']);
    });
