<?php

namespace App\Services;

use App\Models\Profile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Contracts\ProfileRepositoryInterface;

class ProfileService
{
    public function __construct(
        protected ProfileRepositoryInterface $profiles
    ) {}

    public function getProfile(): Profile
    {
        return $this->getOrCreateProfile();
    }

    public function createProfile(array $data): Profile
    {
        $data['user_id'] = Auth::id();

        return $this->profiles->create($data);
    }

    public function updateProfile(array $data): Profile
    {
        $profile = $this->getOrCreateProfile();

        return $this->profiles->update($profile, $data);
    }

    public function uploadAvatar(UploadedFile $avatar): string
    {
        $profile = $this->getOrCreateProfile();

        $this->removeAvatarFromStorage($profile);

        $path = $avatar->store('avatars', 'public');

        $this->profiles->updateAvatar($profile, $path);

        return $path;
    }

    public function deleteAvatar(): bool
    {
        $profile = $this->getOrCreateProfile();

        $this->removeAvatarFromStorage($profile);

        return $this->profiles->updateAvatar($profile, null);
    }

    private function getOrCreateProfile(): Profile
    {
        return $this->profiles->firstOrCreate(
            Auth::id(),
            [
                'first_name' => Auth::user()->name ?? 'User',
                'last_name' => '',
            ]
        );
    }

    private function removeAvatarFromStorage(Profile $profile): void
    {
        if (
            $profile->avatar &&
            Storage::disk('public')->exists($profile->avatar)
        ) {
            Storage::disk('public')->delete($profile->avatar);
        }
    }
}
