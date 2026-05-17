<?php

namespace App\Repo;

use App\Models\Profile;
use Illuminate\Support\Facades\Auth;

class ProfileRepo
{
    public function getProfile()
    {
        $userID = Auth::user()->id;
        $profile = Profile::where('user_id', $userID)->with('user')->firstOrFail();

        return $profile;
    }

    public function createProfile($validated)
    {
        $profile = Profile::create($validated);
        return $profile;
    }

    public function updateProfile($validated)
    {
        $profile = $this->getProfileByUserID();
        $profile->update($validated);
        return $profile;
    }
    public function uploadAvatar($path, $profile)
    {
        $profile->update(['avatar' => $path]);
    }
    public function removeAvatar($profile)
    {
        return $profile->update([
            'avatar' => null
        ]);
    }

    public function getProfileByUserID()
    {
        $profile = Profile::where('user_id', Auth::user()->id)->first();
        return $profile;
    }
}
