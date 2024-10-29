<?php

namespace App\Interfaces;

use App\Models\Event;
use Illuminate\Support\Collection;

interface EventRepositoryInterface
{
    /**
     * Kullanıcının favori işletmelerindeki belirli bir kategoriye göre aktif etkinlikleri getirir.
     *
     * @param int $userId
     * @param int|null $category
     * @param int|null $perPage
     * @param int|null $page
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getActiveEventsByUserFavoriteBusinesses(int $userId, ?int $category, ?int $perPage, ?int $page);
    public function createEvent(array $data,int $userId): Event;
    public function updateEvent(int $eventId, array $data);
    public function deleteEvent(int $eventId): bool;
}
