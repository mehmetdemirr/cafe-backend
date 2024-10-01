<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MenuItemRequest;
use App\Interfaces\MenuItemRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

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
        $businessId = $request->user()->business->id; // İşletme ID'sini al

        // İşletme ID'si yoksa hata döndür
        if (!$businessId) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => 'İşletme bulunamadı.',
                'errors' => null,
            ], 400);
        }

        $data = array_merge($request->validated(), ['business_id' => $businessId]);
        $item = $this->menuItemRepository->create($data);

        return response()->json([
            'data' => $item,
            'success' => true,
            'message' => 'Menü öğesi başarıyla oluşturuldu.',
            'errors' => null,
        ], 201);
    }

    // Update an existing menu item
    public function update(MenuItemRequest $request, $id): JsonResponse
    {
        $item = $this->menuItemRepository->findById($id);

        // Menü öğesi yoksa hata döndür
        if (!$item) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => 'Menü öğesi bulunamadı.',
                'errors' => null,
            ], 404);
        }

        $businessId = $request->user()->business->id; // İşletme ID'sini al

        // İşletme ID'si yoksa hata döndür
        if (!$businessId) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => 'İşletme bulunamadı.',
                'errors' => null,
            ], 400);
        }

        // Menü öğesi işletme ID'si ile eşleşiyor mu kontrol et
        if ($item['business_id'] !== $businessId) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => 'Geçersiz istek.',
                'errors' => 'Bu öğeye erişim izniniz yok.',
            ], 400); // Bad Request
        }

        $updated = $this->menuItemRepository->update($id, $request->validated());

        if (!$updated) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => 'Menü öğesi güncellenirken bir hata oluştu.',
                'errors' => 'Menü öğesi bulunamadı veya güncellenemedi.',
            ], 404);
        }

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

        // Ensure the item belongs to the user's business
        if ($item['business_id'] !== request()->user()->business->id) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => 'Geçersiz istek.',
                'errors' => 'Bu öğeye erişim izniniz yok.',
            ], 400); // Bad Request
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
