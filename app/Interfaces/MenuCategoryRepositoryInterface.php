<?php

namespace App\Interfaces;

use App\Models\MenuCategory;
use Illuminate\Database\Eloquent\Collection;

interface MenuCategoryRepositoryInterface
{
    public function getAllByBusinessId(int $businessId): array;

    public function findById(int $id);

    public function create(array $data): array;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function getCategoriesForCustomerByBusinessId(int $businessId): array;
}
