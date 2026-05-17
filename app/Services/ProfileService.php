<?php

namespace App\Services;

use App\Models\Profile;
use App\Repo\ProfileRepo;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    protected ProfileRepo $profileRepo;

    public function __construct(ProfileRepo $profileRepo)
    {
        $this->profileRepo = $profileRepo;
    }
    public function getProfile()
    {
        $profile = $this->profileRepo->getProfile();
        return $profile;
    }

    public function createProfile($validated)
    {
        $validated['user_id'] = Auth::user()->id;

        $profile = $this->profileRepo->createProfile($validated);
        return $profile;
    }

    public function updateProfile($validated)
    {
        $profile = $this->profileRepo->updateProfile($validated);

        return $profile;
    }
    public function uploadAvatar($request)
    {
        $validated = $request->validated();
        $profile = $this->profileRepo->getProfileByUserID();

        $this->_removeAvatarFromStorage($profile);


        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        $this->profileRepo->uploadAvatar($path, $profile);

        return $path;
    }


    public function deleteAvatar()
    {
        $profile = $this->profileRepo->getProfileByUserID();

        $this->_removeAvatarFromStorage($profile);

        $this->profileRepo->removeAvatar($profile);
    }

    private function _removeAvatarFromStorage($profile)
    {
        //delete avatar from storage
        if ($profile->avatar && Storage::disk('public')->exists($profile->avatar)) {
            Storage::disk('public')->delete($profile->avatar);
        }
    }
}
