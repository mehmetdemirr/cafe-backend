<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface MenuItemRepositoryInterface
{
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function getAllByBusinessId(int $businessId): Collection;
    public function getById(int $id);
}
