<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MenuItemRequest;
use App\Interfaces\MenuItemRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class MenuItemController extends Controller
{
    protected $menuItemRepository;

    public function __construct(MenuItemRepositoryInterface $menuItemRepository)
    {
        $this->menuItemRepository = $menuItemRepository;
    }

    // Retrieve all menu items for the user's business
    public function index(Request $request): JsonResponse
    {
        $businessId = $request->user()->business->id;
        $items = $this->menuItemRepository->getAllByBusinessId($businessId);

        return response()->json([
            'data' => $items,
            'success' => true,
            'message' => 'Menü öğeleri başarıyla alındı.',
            'errors' => null,
        ]);
    }

    // Retrieve menu items by category ID
    public function getByCategoryId(Request $request, $categoryId): JsonResponse
    {
        $businessId = $request->user()->business->id;
        $items = $this->menuItemRepository->getByCategoryIdAndBusinessId($categoryId, $businessId);

        return response()->json([
            'data' => $items,
            'success' => true,
            'message' => 'Kategoriye ait menü öğeleri başarıyla alındı.',
            'errors' => null,
        ]);
    }

    // Retrieve menu items by category ID for a user
    public function getByCategoryForUser(int $businessId, int $categoryId): JsonResponse
    {
        // İşletme ve kategoriye göre menü öğelerini getir
        $items = $this->menuItemRepository->getByCategoryIdAndBusinessId($categoryId, $businessId);

        return response()->json([
            'data' => $items,
            'success' => true,
            'message' => 'Kategoriye ait menü öğeleri başarıyla alındı.',
            'errors' => null,
        ]);
    }

    // Create a new menu item
    public function store(MenuItemRequest $request): JsonResponse
    {
        $businessId = $request->user()->business->id;

        if (!$businessId) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => 'İşletme bulunamadı.',
                'errors' => null,
            ], 400);
        }

        // Dosya yükleme
        if ($request->hasFile('image_url')) {
            $imagePath = $request->file('image_url')->store('menu_items', 'public');
            $fullImageUrl = asset('storage/' . $imagePath); // Tam URL oluşturma
        }

        $data = array_merge($request->validated(), [
            'business_id' => $businessId,
            'image_url' =>  $fullImageUrl ?? null, // Görsel varsa tam URL'yi kaydet
        ]);

        $item = $this->menuItemRepository->create($data);

        return response()->json([
            'data' => $item,
            'success' => true,
            'message' => 'Menü öğesi başarıyla oluşturuldu.',
            'errors' => null,
        ], 200);
    }

    // Update an existing menu item
    public function update(MenuItemRequest $request, $id): JsonResponse
    {
        $item = $this->menuItemRepository->findById($id);

        if (!$item) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => 'Menü öğesi bulunamadı.',
                'errors' => null,
            ], 404);
        }

        $businessId = $request->user()->business->id;

        if (!$businessId || $item['business_id'] !== $businessId) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => 'Geçersiz istek.',
                'errors' => 'Bu öğeye erişim izniniz yok.',
            ], 400);
        }

        // Resim dosyası kontrolü ve eski resmi silme
        if ($request->hasFile('image_url')) {
            if ($item['image_url']) {
                // Eski resim URL'sinden dosya yolunu elde etme
                $oldImagePath = str_replace(asset('storage') . '/', '', $item['image_url']);
                Storage::disk('public')->delete($oldImagePath); // Eski resmi sil
            }
            $imagePath = $request->file('image_url')->store('menu_items', 'public'); // Yeni resmi yükle
            $fullImageUrl = asset('storage/' . $imagePath); // Yeni tam URL
        }
        $updated = $this->menuItemRepository->update($id, array_merge(
            $request->validated(),
            ['image_url' => $fullImageUrl ?? $item['image_url']] // Yeni resim varsa güncelle, yoksa mevcut kalsın
        ));

        return response()->json([
            'data' => $this->menuItemRepository->findById($id),
            'success' => true,
            'message' => 'Menü öğesi başarıyla güncellendi.',
            'errors' => null,
        ]);
    }

    // Delete a menu item
    public function destroy($id): JsonResponse
    {
        $item = $this->menuItemRepository->findById($id);

        if (!$item) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => 'Menü öğesi silinirken bir hata oluştu.',
                'errors' => 'Menü öğesi bulunamadı veya silinemedi.',
            ], 404);
        }

        if ($item['business_id'] !== request()->user()->business->id) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => 'Geçersiz istek.',
                'errors' => 'Bu öğeye erişim izniniz yok.',
            ], 400);
        }

        // Resmi sil
        if ($item['image_url']) {
            // URL'den dosya yolunu almak
            $imagePath = str_replace(asset('storage') . '/', '', $item['image_url']);
            Storage::disk('public')->delete($imagePath); // Resmi sil
        }

        $this->menuItemRepository->delete($id);

        return response()->json([
            'data' => null,
            'success' => true,
            'message' => 'Menü öğesi başarıyla silindi.',
            'errors' => null,
        ], 200);
    }

    // Show a single menu item
    public function show(int $id): JsonResponse
    {
        $item = $this->menuItemRepository->findById($id);

        if (!$item) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => 'Menü öğesi bulunamadı.',
                'errors' => 'Menü öğesi bulunamadı.',
            ], 404);
        }

        return response()->json([
            'data' => $item,
            'success' => true,
            'message' => 'Menü öğesi başarıyla alındı.',
            'errors' => null,
        ]);
    }
}
