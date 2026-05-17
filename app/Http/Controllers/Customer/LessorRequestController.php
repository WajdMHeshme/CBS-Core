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
        $data = $request->validated();

        if ($request->hasFile('identity_front_image')) {

            $data['identity_front_image'] = $request
                ->file('identity_front_image')
                ->store('identity-images/front', 'public');
        }

        if ($request->hasFile('identity_back_image')) {

            $data['identity_back_image'] = $request
                ->file('identity_back_image')
                ->store('identity-images/back', 'public');
        }

        $lessorRequest = $this->service->create($data);

        return response()->json([
            'message' => 'Request submitted successfully',
            'data' => $lessorRequest
        ], 201);
    }
}
