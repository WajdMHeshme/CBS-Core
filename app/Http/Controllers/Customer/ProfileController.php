<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\AvatarRequest;
use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Services\ProfileService;

class ProfileController extends Controller
{
    public function __construct(
        protected ProfileService $profileService
    ) {}

    public function show()
    {
        $profile = $this->profileService->getProfile();

        return response()->json([
            'profile' => new ProfileResource($profile)
        ], 200);
    }

    public function store(StoreProfileRequest $request)
    {
        $profile = $this->profileService->createProfile(
            $request->validated()
        );

        return response()->json([
            'profile' => new ProfileResource($profile)
        ], 201);
    }

    public function update(UpdateProfileRequest $request)
    {
        $profile = $this->profileService->updateProfile(
            $request->validated()
        );

        return response()->json([
            'profile' => new ProfileResource($profile)
        ]);
    }

    public function uploadAvatar(AvatarRequest $request)
    {
        $path = $this->profileService->uploadAvatar(
            $request->file('avatar')
        );

        return response()->json([
            'message' => 'Avatar uploaded successfully',
            'avatar_url' => asset('storage/' . $path),
        ]);
    }

    public function deleteAvatar()
    {
        $this->profileService->deleteAvatar();

        return response()->json([
            'message' => 'Avatar deleted successfully'
        ]);
    }
}
