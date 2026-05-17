<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Visitor\SupportTicketController;

Route::middleware(['auth:sanctum'])
    ->post('/support', [SupportTicketController::class, 'store']);
