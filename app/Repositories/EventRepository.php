<?php

namespace App\Repositories;

use App\Interfaces\EventRepositoryInterface;
use App\Models\Event;
use App\Models\UserFavoriteBusiness;
use Carbon\Carbon;

class EventRepository implements EventRepositoryInterface{

   /**
     * Kullanıcının favori işletmelerindeki belirli bir kategoriye göre aktif etkinlikleri getirir.
     *
     * @param int $userId
     * @param int|null $category
     * @param int|null $perPage
     * @param int|null $page
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getActiveEventsByUserFavoriteBusinesses(int $userId, ?int $category = null, ?int $perPage = null, ?int $page = null)
    {
        // Favori işletmelere ait aktif etkinlikleri getiren sorgu
        $query = Event::whereHas('business', function ($query) use ($userId) {
            $query->whereIn('id', function ($subQuery) use ($userId) {
                $subQuery->select('business_id')
                        ->from('user_favorite_businesses')
                        ->where('user_id', $userId);
            });
        })
        // ->where('start_date', '>=', now()); // Aktif etkinlikler için
        ->where('end_date', '>=', now()); // Şu anki zamandan sonra bitmemiş olmalı

        // Eğer kategori verilmişse, filtrele
        if ($category !== null) {
            $query->where('category', $category);
        }

        // Sayfalama
        return $query->paginate($perPage ?? 10, ['*'], 'page', $page ?? 1);
    }

    public function createEvent(array $data,int $userId): Event
    {  
        $favoriteBusinessIds = UserFavoriteBusiness::where('user_id', $userId)->pluck('business_id');

        if (!in_array($data['business_id'], $favoriteBusinessIds->toArray())) {
            throw new \Exception('Kullanıcı yalnızca favori işletmelerine etkinlik oluşturabilir.');
        }

        return Event::create($data);
    }

    public function updateEvent(int $eventId, array $data)
    {
        $event = Event::find($eventId);
        if ($event) {
            $event->update($data);
            return $event;
        }
        return null;
    }

    public function deleteEvent(int $eventId): bool
    {
        return Event::destroy($eventId) > 0;
    }
}
