<?php


use App\Http\Controllers\Customer\ProRequestController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::post('/pro-request', [ProRequestController::class, 'store']);
});
