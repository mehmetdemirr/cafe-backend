<?php

namespace App\Repositories;

use App\Http\Controllers\Controller;
use App\Http\Requests\MenuCategoryRequest;
use App\Interfaces\MenuCategoryRepositoryInterface;
use App\Models\MenuCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Collection;

class MenuCategoryRepository
{
    public function create(array $data): MenuCategory
    {
        return MenuCategory::create($data);
    }

    public function update(int $id, array $data): MenuCategory
    {
        $category = $this->findById($id);
        $category->update($data);
        return $category;
    }

    public function delete(int $id): void
    {
        $category = $this->findById($id);
        $category->delete();
    }

    public function getAllByBusinessId(int $businessId): Collection
    {
        return MenuCategory::where('business_id', $businessId)->get();
    }

    public function findById(int $id): ?MenuCategory
    {
        return MenuCategory::find($id);
    }
}
