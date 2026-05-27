<?php

namespace App\Repositories\Eloquent;

use App\Models\Profile;
use App\Repositories\Contracts\ProfileRepositoryInterface;

class ProfileRepository implements ProfileRepositoryInterface
{
    public function findByUserId(int $userId): ?Profile
    {
        return Profile::with('user')
            ->where('user_id', $userId)
            ->first();
    }

    public function create(array $data): Profile
    {
        return Profile::create($data);
    }

    public function update(Profile $profile, array $data): Profile
    {
        $profile->update($data);

        return $profile->fresh();
    }

    public function updateAvatar(Profile $profile, ?string $path): bool
    {
        return $profile->update([
            'avatar' => $path
        ]);
    }

    public function firstOrCreate(int $userId, array $data): Profile
    {
        return Profile::firstOrCreate(
            ['user_id' => $userId],
            $data
        );
    }
}
