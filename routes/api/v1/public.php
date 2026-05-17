<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Visitor\CarController;

Route::get('/cars', [CarController::class, 'index']);
Route::get('/cars/{car}', [CarController::class, 'show']);
