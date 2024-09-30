<?php

namespace App\Repositories;

use App\Http\Controllers\Controller;
use App\Http\Requests\MenuCategoryRequest;
use App\Interfaces\MenuCategoryRepositoryInterface;
use App\Models\MenuCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Collection;

class MenuCategoryRepository implements MenuCategoryRepositoryInterface
{
    public function getAllByBusinessId(int $businessId): array
    {
        return MenuCategory::where('business_id', $businessId)->get()->toArray();
    }

    public function findById(int $id): ?array
    {
        return MenuCategory::find($id)?->toArray();
    }

    public function create(array $data): array
    {
        return MenuCategory::create($data)->toArray();
    }

    public function update(int $id, array $data): bool
    {
        $category = MenuCategory::find($id);
        if ($category) {
            return $category->update($data);
        }
        return false;
    }

    public function delete(int $id): bool
    {
        return MenuCategory::destroy($id);
    }

    public function getCategoriesForCustomerByBusinessId(int $businessId): array
    {
        // This method will retrieve categories for customers (read-only)
        return MenuCategory::where('business_id', $businessId)
            ->get()->toArray();
    }
}
