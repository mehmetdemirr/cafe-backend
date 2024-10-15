<?php

namespace App\Interfaces;

use App\Models\Business;

interface BusinessEntryRepositoryInterface
{
    public function findByQrCode(string $qrCode): ?Business;
    public function enterCafe(int $userId, string $qrCode): bool;

    public function leaveCafe(int $userId, int $businessId): bool;

    public function isUserInCafe(int $userId, int $businessId): bool;

    public function getUsersInCafe(int $businessId): array;
}
