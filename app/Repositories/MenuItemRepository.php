<?php

namespace App\Repositories;

use App\Interfaces\MenuItemRepositoryInterface;
use App\Models\MenuItem;

class MenuItemRepository implements MenuItemRepositoryInterface
{
    public function create(array $data)
    {
        return MenuItem::create($data);
    }

    public function update(int $id, array $data)
    {
        $item = $this->getById($id);
        $item->update($data);
        return $item;
    }

    public function delete(int $id)
    {
        $item = $this->getById($id);
        $item->delete();
    }

    public function getAllByBusinessId(int $businessId) 
    {
        return MenuItem::where('business_id', $businessId)->get();
    }

    public function getById(int $id)
    {
        return MenuItem::findOrFail($id);
    }
}
