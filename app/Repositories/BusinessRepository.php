<?php

namespace App\Repositories;
use App\Interfaces\BusinessRepositoryInterface;
use App\Models\Business;
use App\Models\BusinessRating;
use App\Models\User;
use App\Models\UserFavoriteBusiness;
use App\Models\CafeRating;

class BusinessRepository implements BusinessRepositoryInterface
{
    public function all(): array
    {
        return Business::with(['owner', 'menuCategories', 'campaigns', 'events', 'notifications', 'ratings'])->get()->toArray();
    }

    public function find(int $id): ?Business
    {
        return Business::with(['owner', 'menuCategories', 'campaigns', 'events', 'notifications', 'ratings'])->find($id);
    }

    public function create(array $data): Business
    {
        return Business::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $business = $this->find($id);
        if ($business) {
            return $business->update($data);
        }
        return false;
    }

    public function delete(int $id): bool
    {
        $business = $this->find($id);
        if ($business) {
            return $business->delete();
        }
        return false;
    }

    public function addToFavorites(int $businessId, int $userId): bool
    {
        return UserFavoriteBusiness::firstOrCreate(['business_id' => $businessId, 'user_id' => $userId]) ? true : false;
    }

    public function removeFromFavorites(int $businessId, int $userId): bool
    {
        return UserFavoriteBusiness::where('business_id', $businessId)->where('user_id', $userId)->delete() > 0;
    }

    public function rateBusiness(int $businessId, int $userId, int $rating, ?string $comment = null): bool
    {
        // İşletmenin var olup olmadığını kontrol et
        if (!Business::where('id', $businessId)->exists()) {
            return false; // İşletme bulunamadı
        }

        $cafeRating = BusinessRating::updateOrCreate(
            ['business_id' => $businessId, 'user_id' => $userId],
            [
                'rating' => $rating,
                'comment' => $comment,
            ]
        );
        
        return (bool)$cafeRating;
    }

    public function getBusinessRatings(int $businessId): array
    {
        return BusinessRating::where('business_id', $businessId)->get()->toArray();
    }

    public function getFavoriteBusinesses(int $userId): array
    {
        return UserFavoriteBusiness::with('business')
            ->where('user_id', $userId)
            ->get()
            ->pluck('business')
            ->toArray();
    }

    public function exists(array $conditions): bool
    {
        return Business::where($conditions)->exists(); // Belirtilen koşullara göre kayıt var mı kontrolü
    }

    public function isFavorited($businessId, $userId):bool
    {
        return UserFavoriteBusiness::where('business_id', $businessId)
                                    ->where('user_id', $userId)
                                    ->exists();
    }
}
