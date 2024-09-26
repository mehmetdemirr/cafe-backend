<?php

namespace App\Interfaces;

use App\Models\Business;

interface BusinessRepositoryInterface
{
    public function create(array $data): Business;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function find(int $id): ?Business;
    public function all(): array;
    public function addToFavorites(int $businessId, int $userId): bool;
    public function removeFromFavorites(int $businessId, int $userId): bool;
    public function rateBusiness(int $businessId, int $userId, int $rating): bool;
    public function getBusinessRatings(int $businessId): array;
    public function getFavoriteBusinesses(int $userId): array;
    public function exists(array $conditions): bool;
    public function isFavorited($businessId, $userId) : bool;
}
