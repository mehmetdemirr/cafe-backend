<?php

namespace App\Repositories;

use App\Interfaces\MenuItemRepositoryInterface;
use App\Models\MenuCategory;
use App\Models\MenuCategoryView;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Request;

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
        $ipAddress = Request::ip();
        $userAgent = Request::header('User-Agent');
        
        // İlgili kategori kaydını bul
        $category = MenuCategory::find($categoryId);

        if ($category) {
            // Görünüm sayısını artır
            $category->increment('views');
            // Aynı IP'den aynı kategorinin tekrar kaydedilmesini engellemek için kontrol
            $existingView = MenuCategoryView::where('menu_category_id', $category->id)
                ->where('ip_address', $ipAddress)
                ->first();

            if (!$existingView) {
                // Görünümü kaydet
                MenuCategoryView::create([
                    'menu_category_id' => $category->id,
                    'ip_address' => $ipAddress,
                    'user_agent' => $userAgent,
                    'viewed_at' => now(),
                ]);
            }
        }
        
        return MenuItem::where('menu_category_id', $categoryId)
            ->where('business_id', $businessId)
            ->get()
            ->toArray();
    }
}
