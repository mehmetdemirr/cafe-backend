<?php

namespace App\Repositories;

use App\Interfaces\MenuItemRepositoryInterface;
use App\Models\MenuItem;

class MenuItemRepository implements MenuItemRepositoryInterface
{
    public function getAllByBusinessId(int $businessId): array
    {
        return MenuItem::where('business_id', $businessId)->get()->toArray();
    }

    public function findById(int $id): ?array
    {
        return MenuItem::find($id)?->toArray();
    }

    public function create(array $data): array
    {
        return MenuItem::create($data)->toArray();
    }

    public function update(int $id, array $data): bool
    {
        $item = MenuItem::find($id);
        if ($item) {
            return $item->update($data);
        }
        return false;
    }

    public function delete(int $id): bool
    {
        return MenuItem::destroy($id);
    }

    public function getByCategoryIdAndBusinessId(int $categoryId, int $businessId): array
    {
        return MenuItem::where('menu_category_id', $categoryId)
            ->where('business_id', $businessId)
            ->get()
            ->toArray();
    }
}
