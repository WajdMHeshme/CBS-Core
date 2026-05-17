<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\AvatarRequest;
use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use App\Services\ProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    protected ProfileService $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }
    public function show()
    {

        $profile = $this->profileService->getProfile();

        return response()->json(['profile' => new ProfileResource($profile), 200]);
    }

    public function store(StoreProfileRequest $request)
    {
        $validated = $request->validated();
        $isExists = Profile::where('user_id', Auth::user()->id)->exists();
        if ($isExists) {
            return response()->json([
                'message' => 'Profile already exists'
            ], 409);
        }
        $profile = $this->profileService->createProfile($validated);

        return response()->json([
            'profile' => new ProfileResource($profile)
        ], 201,);
    }

    public function update(UpdateProfileRequest $request)
    {
        $validated = $request->validated();

        $profile = $this->profileService->updateProfile($validated);

        return response()->json([
            'profile' => new ProfileResource($profile)
        ], 200,);
    }

    public function uploadAvatar(AvatarRequest $request)
    {

        $path = $this->profileService->uploadAvatar($request);

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
