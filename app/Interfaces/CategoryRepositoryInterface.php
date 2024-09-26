<?php

namespace App\Interfaces;

interface CategoryRepositoryInterface
{
    public function all(): array;

    public function find(int $id): ?object;

    public function create(array $data): object;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;
    
    public function getUserFavoriteCategories(int $userId): array;
    
    public function addCategoryToFavorites(int $categoryId, int $userId): bool;

    public function removeCategoryFromFavorites(int $categoryId, int $userId): bool;
}
