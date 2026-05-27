<?php

namespace App\Repositories\Contracts;

use App\Models\Profile;

interface ProfileRepositoryInterface
{
    public function findByUserId(int $userId): ?Profile;

    public function create(array $data): Profile;

    public function update(Profile $profile, array $data): Profile;

    public function updateAvatar(Profile $profile, ?string $path): bool;

    public function firstOrCreate(int $userId, array $data): Profile;
}
