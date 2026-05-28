<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;

Route::middleware('auth:sanctum')->group(function () {

    Route::get(
        '/notifications',
        [NotificationController::class, 'index']
    );

    Route::get(
        '/notifications/unread-count',
        [NotificationController::class, 'unreadCount']
    );

    Route::post(
        '/notifications/{id}/read',
        [NotificationController::class, 'markAsRead']
    );

    // test endpoint
    Route::post(
        '/notifications/test',
        [NotificationController::class, 'test']
    );
});
