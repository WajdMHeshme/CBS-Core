<?php

namespace App\Services;

use App\Repo\ProfileRepo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    protected ProfileRepo $profileRepo;

    public function __construct(ProfileRepo $profileRepo)
    {
        $this->profileRepo = $profileRepo;
    }


    private function getOrCreateProfile()
    {
        return $this->profileRepo->getOrCreateProfile();
    }


    public function getProfile()
    {
        return $this->getOrCreateProfile();
    }

    public function createProfile($validated)
    {
        $validated['user_id'] = Auth::id();

        return $this->profileRepo->createProfile($validated);
    }


    public function updateProfile($validated)
    {
        $profile = $this->getOrCreateProfile();

        return $this->profileRepo->updateProfile($profile, $validated);
    }


    public function uploadAvatar($request)
    {
        $profile = $this->getOrCreateProfile();

        $this->_removeAvatarFromStorage($profile);

        $path = $request->file('avatar')->store('avatars', 'public');

        $this->profileRepo->uploadAvatar($profile, $path);

        return $path;
    }


    public function deleteAvatar()
    {
        $profile = $this->getOrCreateProfile();

        $this->_removeAvatarFromStorage($profile);

        $this->profileRepo->removeAvatar($profile);
    }


    private function _removeAvatarFromStorage($profile)
    {
        if ($profile->avatar && Storage::disk('public')->exists($profile->avatar)) {
            Storage::disk('public')->delete($profile->avatar);
        }
    }
}
