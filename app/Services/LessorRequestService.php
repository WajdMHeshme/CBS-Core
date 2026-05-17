<?php

namespace App\Services;

use App\Models\LessorRequest;

class LessorRequestService
{
    public function create(array $data): LessorRequest
    {
        return LessorRequest::create([
            'user_id' => auth()->id(),

            'business_name' => $data['business_name'] ?? null,
            'phone' => $data['phone'] ?? null,
            'message' => $data['message'] ?? null,

            'identity_front_image' => $data['identity_front_image'] ?? null,
            'identity_back_image' => $data['identity_back_image'] ?? null,
        ]);
    }
    public function updateStatus(
        LessorRequest $lessorRequest,
        string $status
    ): LessorRequest {

        $lessorRequest->update([
            'status' => $status
        ]);

        if ($status === 'approved') {
            $lessorRequest->user->assignRole('lessor');
        }

        return $lessorRequest->fresh();
    }
}
