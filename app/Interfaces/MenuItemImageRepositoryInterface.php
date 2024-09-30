<?php

namespace App\Interfaces;

interface MenuItemImageRepositoryInterface
{
    public function getAllByMenuItemId(int $menuItemId): array;

    public function findById(int $id): ?array;

    public function create(array $data): array;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;
}
