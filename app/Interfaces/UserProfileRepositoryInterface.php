<?php

namespace App\Interfaces;

use App\Models\ProfileDetail;

interface UserProfileRepositoryInterface
{
    public function create(array $data): bool;

    public function update(int $userId, array $data): bool;

    public function exists(int $userId): bool;

    public function delete(int $userId): bool;

    public function getProfile(int $userId): ?ProfileDetail;
}
