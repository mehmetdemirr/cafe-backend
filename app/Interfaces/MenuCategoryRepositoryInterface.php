<?php

namespace App\Interfaces;

use App\Models\MenuCategory;
use Illuminate\Database\Eloquent\Collection;

interface MenuCategoryRepositoryInterface
{
    public function create(array $data): MenuCategory;
    public function update(int $id, array $data): MenuCategory;
    public function delete(int $id): void;
    public function getAllByBusinessId(int $businessId): Collection;
    public function findById(int $id): ?MenuCategory;
}
