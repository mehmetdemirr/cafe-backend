<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface MenuItemRepositoryInterface
{
    public function getAllByBusinessId(int $businessId): array;

    public function findById(int $id): ?array;

    public function create(array $data): array;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function getByCategoryIdAndBusinessId(int $categoryId, int $businessId): array;
}
