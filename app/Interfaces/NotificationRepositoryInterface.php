<?php

namespace App\Interfaces;

use App\Models\Notification;

interface NotificationRepositoryInterface
{
    public function create(array $data): Notification;
    public function findById(int $id): ?Notification;
    public function getAll(): array;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function getByUserId(int $userId): array;
}
