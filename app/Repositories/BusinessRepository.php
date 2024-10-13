<?php

namespace App\Repositories;
use App\Interfaces\BusinessRepositoryInterface;
use App\Models\Business;
use App\Models\BusinessRating;
use App\Models\User;
use App\Models\UserFavoriteBusiness;

class BusinessRepository implements BusinessRepositoryInterface
{
    public function all(): array
    {
        return Business::with(['owner'])->get()->toArray();
    }

    public function getNearbyBusinesses(int $userId,float $latitude, float $longitude, float $radius): array
    {
        $favoriteBusinessIds = UserFavoriteBusiness::where('user_id', $userId)
        ->pluck('business_id'); // Favori iş yerlerinin ID'lerini al

        // Earth's radius in kilometers
        $earthRadius = 6371;


        // Find businesses within the radius
        return Business::select('*')
            ->selectRaw(
                "(? * acos(cos(radians(?)) * cos(radians(location_latitude)) * cos(radians(location_longitude) - radians(?)) +
                sin(radians(?)) * sin(radians(location_latitude)))) AS distance",
                [$earthRadius, $latitude, $longitude, $latitude]
            )
            ->whereNotIn('id', $favoriteBusinessIds) // Favori iş yerlerini hariç tut
            ->having('distance', '<', $radius)
            ->get()
            ->toArray();
    }

    public function find(int $id): ?Business
    {
        return Business::with(['owner'])->find($id);
    }

    // Slug ile business bulma metodu
    public function findBySlug(string $slug): ?Business
    {
        return Business::with(['owner'])
                       ->where('slug', $slug)
                       ->first();
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
        return UserFavoriteBusiness::with(['business' => function($query) {
                $query->withCount('ratings'); // Değerlendirme sayısını al
            }])
            ->where('user_id', $userId)
            ->get()
            ->pluck('business')
            ->map(function ($business) {
                // İşletme bilgilerini ve değerlendirme sayısını döndür
                return [
                    'id' => $business->id,
                    'name' => $business->name,
                    'address' => $business->address,
                    'qr_code' => $business->qr_code,
                    'ratings_count' => $business->ratings_count, // Değerlendirme sayısı
                ];
            })
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
