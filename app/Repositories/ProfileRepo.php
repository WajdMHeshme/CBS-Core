<?php

namespace App\Repo;

use App\Models\Profile;
use Illuminate\Support\Facades\Auth;

class ProfileRepo
{
    public function getProfile()
    {
        return Profile::where('user_id', Auth::id())
            ->with('user')
            ->first();
    }

    public function createProfile($validated)
    {
        return Profile::create($validated);
    }

    public function updateProfile($profile, $validated)
    {
        $profile->update($validated);
        return $profile;
    }

    public function uploadAvatar($path, $profile)
    {
        $profile->update(['avatar' => $path]);
    }

    public function removeAvatar($profile)
    {
        $profile->update(['avatar' => null]);
    }

    public function getOrCreateProfile()
    {
        return Profile::firstOrCreate(
            ['user_id' => Auth::id()],
            [
                'first_name' => Auth::user()->name ?? 'User',
                'last_name' => '',
            ]
        );
    }
}
