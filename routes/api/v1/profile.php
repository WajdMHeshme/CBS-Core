<?php
//Eng.waeel

use App\Http\Controllers\Customer\ProfileController;
use Illuminate\Support\Facades\Route;

    Route::middleware(['auth:sanctum','role:customer'])->prefix('profiles')
    ->group(function (){

     Route::post('',[ProfileController::class,'store']);

     Route::get('',[ProfileController::class,'show']);

     Route::put('',[ProfileController::class,'update']);

     Route::post('/avatars',[ProfileController::class,'uploadAvatar']);

     Route::delete('/avatars',[ProfileController::class,'deleteAvatar']);
    });
