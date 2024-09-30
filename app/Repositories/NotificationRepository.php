<?php

namespace App\Repositories;
use App\Interfaces\NotificationRepositoryInterface;
use App\Models\Notification;

class NotificationRepository implements NotificationRepositoryInterface
{
    public function create(array $data): Notification
    {
        return Notification::create($data);
    }

    public function findById(int $id): ?Notification
    {
        return Notification::find($id);
    }

    public function getAll(): array
    {
        return Notification::all()->toArray();
    }

    public function update(int $id, array $data): bool
    {
        $notification = $this->findById($id);

        if ($notification) {
            return $notification->update($data);
        }

        return false;
    }

    public function delete(int $id): bool
    {
        $notification = $this->findById($id);

        if ($notification) {
            return $notification->delete();
        }

        return false;
    }

    public function getByUserId(int $userId): array
    {
        return Notification::where('user_id', $userId)->get()->toArray();
    }
}
