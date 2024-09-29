<?php

namespace App\Repositories;

use App\Interfaces\CategoryRepositoryInterface;
use App\Models\Category;
use App\Models\UserFavoriteCategory;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function all(): array
    {
        return Category::all()->toArray();
    }

    public function find(int $id): ?object
    {
        return Category::find($id);
    }

    public function create(array $data): object
    {
        return Category::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $category = $this->find($id);
        if ($category) {
            return $category->update($data);
        }
        return false;
    }

    public function delete(int $id): bool
    {
        $category = $this->find($id);
        if ($category) {
            return $category->delete();
        }
        return false;
    }

    public function getUserFavoriteCategories(int $userId): array
    {
        return Category::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get()->toArray();
    }

    public function addCategoryToFavorites(int $categoryId, int $userId): bool
    {
        return UserFavoriteCategory::firstOrCreate(['category_id' => $categoryId, 'user_id' => $userId]) ? true : false;
    }

    public function removeCategoryFromFavorites(int $categoryId, int $userId): bool
    {
        return UserFavoriteCategory::where('category_id', $categoryId)->where('user_id', $userId)->delete() > 0;
    }

    public function isCategoryInFavorites(int $categoryId, int $userId): bool
    {
        return UserFavoriteCategory::where('category_id', $categoryId)
                                ->where('user_id', $userId)
                                ->exists();
    }
}
