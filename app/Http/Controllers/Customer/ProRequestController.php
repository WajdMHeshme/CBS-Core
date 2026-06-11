<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProRequest;
use Illuminate\Support\Facades\Auth;
use App\Services\ProRequestService;

class ProRequestController extends Controller
{
public function store(StoreProRequest $request, ProRequestService $service)
{
    $user = Auth::user();

    if ($user->is_pro) {
        return back()->withErrors([
            'message' => 'You are already a Pro user'
        ]);
    }

    $existing = $user->proRequest()
        ->where('status', 'pending')
        ->first();

    if ($existing) {
        return back()->withErrors([
            'message' => 'You already have a pending request'
        ]);
    }

    $path = $request->file('payment_proof')
        ->store('pro-requests', 'public');

    $service->create([
        'payment_proof' => $path,
        'notes' => $request->notes,
    ]);

    return back()->with('success', 'Request sent successfully');
}
}
