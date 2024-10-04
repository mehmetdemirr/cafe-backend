<?php

namespace App\Repositories;

use App\Interfaces\UserProfileRepositoryInterface;
use App\Models\ProfileDetail;

class UserProfileRepository implements UserProfileRepositoryInterface
{
    public function create(array $data): bool
    {
        return ProfileDetail::create($data) ? true : false;
    }

    public function update(int $userId, array $data): bool
    {
        $profile = ProfileDetail::where('user_id', $userId)->first();

        if ($profile) {
            return $profile->update($data);
        }

        return false;
    }

    public function exists(int $userId): bool
    {
        return ProfileDetail::where('user_id', $userId)->exists();
    }

    public function delete(int $userId): bool
    {
        $profile = ProfileDetail::where('user_id', $userId)->first();

        if ($profile) {
            return $profile->delete();
        }

        return false;
    }

    public function getProfile(int $userId): ?ProfileDetail
    {
        return ProfileDetail::with('user') // 'user' ilişkisini beraber getirir
        ->where('user_id', $userId)
        ->first();
    }
}
