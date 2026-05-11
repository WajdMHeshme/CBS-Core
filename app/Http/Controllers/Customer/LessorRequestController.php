<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLessorRequest;
use App\Services\LessorRequestService;

class LessorRequestController extends Controller
{
    public function __construct(
        protected LessorRequestService $service
    ) {}

    public function store(StoreLessorRequest $request)
    {
        $lessorRequest = $this->service->create(
            $request->validated()
        );

        return response()->json([
            'message' => 'Request submitted successfully',
            'data' => $lessorRequest
        ], 201);
    }
}
