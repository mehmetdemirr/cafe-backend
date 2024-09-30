<?php

namespace App\Repositories;

use App\Models\MenuItemImage;

class MenuItemImageRepository
{
    public function getAllByMenuItemId(int $menuItemId): array
    {
        return MenuItemImage::where('menu_item_id', $menuItemId)->get()->toArray();
    }

    public function findById(int $id): ?array
    {
        return MenuItemImage::find($id)?->toArray();
    }

    public function create(array $data): array
    {
        return MenuItemImage::create($data)->toArray();
    }

    public function update(int $id, array $data): bool
    {
        $image = MenuItemImage::find($id);
        if ($image) {
            return $image->update($data);
        }
        return false;
    }

    public function delete(int $id): bool
    {
        return MenuItemImage::destroy($id);
    }
}
