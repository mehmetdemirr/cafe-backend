<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\MenuItemImageRequest;
use App\Interfaces\MenuItemImageRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class MenuItemImageController extends Controller
{
    protected $menuItemImageRepository;

    public function __construct(MenuItemImageRepositoryInterface $menuItemImageRepository)
    {
        $this->menuItemImageRepository = $menuItemImageRepository;
    }

    // Menü öğesine ait tüm görselleri getir
    public function index(Request $request, $menuItemId): JsonResponse
    {
        $images = $this->menuItemImageRepository->getAllByMenuItemId($menuItemId);

        return response()->json([
            'data' => $images,
            'success' => true,
            'message' => 'Menü öğesine ait görseller başarıyla alındı.',
            'errors' => null,
        ]);
    }

    // Menü öğesine yeni bir görsel ekle
    public function store(MenuItemImageRequest $request): JsonResponse
    {
        // Dosyayı yükle
        if ($request->hasFile('image_url')) {
            $path = $request->file('image_url')->store('menu_item_images', 'public');

            // Veritabanına kaydet
            $data = [
                'menu_item_id' => $request->validated()['menu_item_id'],
                'image_url' => $path,
            ];
            $image = $this->menuItemImageRepository->create($data);

            return response()->json([
                'data' => $image,
                'success' => true,
                'message' => 'Görsel başarıyla oluşturuldu.',
                'errors' => null,
            ], 201);
        }

        return response()->json([
            'data' => null,
            'success' => false,
            'message' => 'Görsel yüklenirken bir hata oluştu.',
            'errors' => 'Görsel dosyası gereklidir.',
        ], 400);
    }

    // Görsel güncelle
    public function update(MenuItemImageRequest $request, $id): JsonResponse
    {
        // Mevcut görseli bul
        $image = $this->menuItemImageRepository->findById($id);
        if (!$image) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => 'Görsel bulunamadı.',
                'errors' => 'Görsel bulunamadı.',
            ], 404);
        }

        // Yeni görsel yükleme
        if ($request->hasFile('image_url')) {
            // Eski görseli sil
            Storage::disk('public')->delete($image['image_url']);
            $path = $request->file('image_url')->store('menu_item_images', 'public');
            $data = ['image_url' => $path];
        } else {
            $data = [];
        }

        // Görsel güncelle
        $updated = $this->menuItemImageRepository->update($id, $data);
        if (!$updated) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => 'Görsel güncellenirken bir hata oluştu.',
                'errors' => 'Görsel güncellenemedi.',
            ], 404);
        }

        return response()->json([
            'data' => $this->menuItemImageRepository->findById($id),
            'success' => true,
            'message' => 'Görsel başarıyla güncellendi.',
            'errors' => null,
        ]);
    }

    // Görsel sil
    public function destroy($id): JsonResponse
    {
        // Mevcut görseli bul
        $image = $this->menuItemImageRepository->findById($id);
        if (!$image) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => 'Görsel bulunamadı.',
                'errors' => 'Görsel bulunamadı.',
            ], 404);
        }

        // Görseli sil
        $deleted = $this->menuItemImageRepository->delete($id);
        Storage::disk('public')->delete($image['image_url']);

        if (!$deleted) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => 'Görsel silinirken bir hata oluştu.',
                'errors' => 'Görsel silinemedi.',
            ], 404);
        }

        return response()->json([
            'data' => null,
            'success' => true,
            'message' => 'Görsel başarıyla silindi.',
            'errors' => null,
        ], 200);
    }

    // Tek bir görseli göster
    public function show(int $id): JsonResponse
    {
        $image = $this->menuItemImageRepository->findById($id);

        if (!$image) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => 'Görsel bulunamadı.',
                'errors' => 'Görsel bulunamadı.',
            ], 404);
        }

        return response()->json([
            'data' => $image,
            'success' => true,
            'message' => 'Görsel başarıyla alındı.',
            'errors' => null,
        ]);
    }
}
