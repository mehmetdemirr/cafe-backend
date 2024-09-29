<?php

namespace App\Repositories;

use App\Interfaces\LoyaltyPointRepositoryInterface;
use App\Models\LoyaltyPoint;

class LoyaltyPointRepository implements LoyaltyPointRepositoryInterface
{
    public function all(): array
    {
        return LoyaltyPoint::all()->toArray();
    }

    public function find(int $id): ?array
    {
        return LoyaltyPoint::find($id)?->toArray();
    }

    public function create(array $data): array
    {
        $loyaltyPoint = LoyaltyPoint::create($data);
        return $loyaltyPoint->toArray();
    }

    public function update(int $id, array $data): bool
    {
        $loyaltyPoint = $this->find($id);
        if ($loyaltyPoint) {
            return LoyaltyPoint::where('id', $id)->update($data);
        }
        return false;
    }

    public function delete(int $id): bool
    {
        $loyaltyPoint = $this->find($id);
        if ($loyaltyPoint) {
            return LoyaltyPoint::destroy($id);
        }
        return false;
    }

    public function findByUserId(int $userId): array
    {
        return LoyaltyPoint::where('user_id', $userId)->get()->toArray();
    }
}
