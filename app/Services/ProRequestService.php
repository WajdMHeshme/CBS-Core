<?php

namespace App\Services;

use App\Models\ProRequest;
use Illuminate\Support\Facades\Auth;

class ProRequestService
{
    public function create(array $data)
    {
        return ProRequest::create([
            'user_id' => Auth::id(),
            'payment_proof' => $data['payment_proof'],
            'notes' => $data['notes'] ?? null,
            'status' => 'pending',
        ]);
    }

    public function approve(ProRequest $request)
    {
        $request->update([
            'status' => 'approved',
            'reviewed_at' => now(),
        ]);

        $request->user->update([
            'is_pro' => true,
        ]);

        return $request;
    }

    public function reject(ProRequest $request, ?string $reason = null)
    {
        $request->update([
            'status' => 'rejected',
            'admin_notes' => $reason,
            'reviewed_at' => now(),
        ]);

        return $request;
    }
}
