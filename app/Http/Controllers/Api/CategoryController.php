<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Interfaces\CategoryRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    // Tüm kategorileri listele
    public function index(): JsonResponse
    {
        $categories = $this->categoryRepository->all();
        return response()->json([
            'success' => true,
            'data' => $categories,
            'message' => 'Kategoriler listelendi.',
            'errors' => null,
        ], 200);
    }

    // Bir kategori getir
    public function show($id): JsonResponse
    {
        $category = $this->categoryRepository->find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Kategori bulunamadı.',
                'errors' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $category,
            'message' => 'Kategori getirildi.',
            'errors' => null,
        ], 200);
    }

    // Yeni kategori ekle
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        // Yüklenen resmin yolunu al
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('favorite', 'public');
        }

        // Kategori verilerini oluştur
        $categoryData = $request->validated();
        $categoryData['image_url'] = $imagePath; // Resim yolunu ekleyin

        // Kategori oluşturma
        $category = $this->categoryRepository->create($categoryData);
        
        return response()->json([
            'success' => true,
            'data' => $category,
            'message' => 'Kategori başarıyla oluşturuldu.',
            'errors' => null,
        ], 201);
    }

    // Kategoriyi güncelle
    public function update($id, Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
        ]);

        $updated = $this->categoryRepository->update($id, $data);

        return $updated ? response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Kategori başarıyla güncellendi.',
            'errors' => null,
        ], 200) : response()->json([
            'success' => false,
            'data' => null,
            'message' => 'Kategori güncellenemedi.',
            'errors' => null,
        ], 404);
    }

    // Kategoriyi sil
    public function destroy($id): JsonResponse
    {
        $deleted = $this->categoryRepository->delete($id);

        return $deleted ? response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Kategori başarıyla silindi.',
            'errors' => null,
        ], 200) : response()->json([
            'success' => false,
            'data' => null,
            'message' => 'Kategori silinemedi.',
            'errors' => null,
        ], 404);
    }

    // Kullanıcının favori kategorilerini listele
    public function userFavorites(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        $favorites = $this->categoryRepository->getUserFavoriteCategories($userId);
        return response()->json([
            'success' => true,
            'data' => $favorites,
            'message' => 'Kullanıcının favori kategorileri listelendi.',
            'errors' => null,
        ], 200);
    }

    // Kullanıcının favorilerine kategori ekle
    public function addToFavorites( Request $request,$categoryId): JsonResponse
    {
        $userId = $request->user()->id;

    // Kategori var mı kontrol et
    $categoryExists = $this->categoryRepository->find($categoryId);

    if (!$categoryExists) {
        return response()->json([
            'success' => false,
            'data' => null,
            'message' => 'Kategori bulunamadı.',
            'errors' => null,
        ], 404);
    }

    // Kategori zaten favorilerde mi kontrol et
    $alreadyFavorite = $this->categoryRepository->isCategoryInFavorites($categoryId, $userId);

    if ($alreadyFavorite) {
        return response()->json([
            'success' => false,
            'data' => null,
            'message' => 'Kategori zaten favorilerde.',
            'errors' => null,
        ], 200);
    }

    // Kategoriyi favorilere ekle
    $added = $this->categoryRepository->addCategoryToFavorites($categoryId, $userId);

    return $added ? response()->json([
        'success' => true,
        'data' => null,
        'message' => 'Kategori favorilere eklendi.',
        'errors' => null,
    ], 200) : response()->json([
        'success' => false,
        'data' => null,
        'message' => 'Kategori favorilere eklenemedi.',
        'errors' => null,
    ], 404);
    }

    // Kullanıcının favorilerinden kategori çıkar
    public function removeFromFavorites(Request $request,$categoryId): JsonResponse
    {
        $userId = $request->user()->id;
        $removed = $this->categoryRepository->removeCategoryFromFavorites($categoryId, $userId);
        return $removed ? response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Kategori favorilerden çıkarıldı.',
            'errors' => null,
        ], 200) : response()->json([
            'success' => false,
            'data' => null,
            'message' => 'Kategori favorilerden çıkarılamadı.',
            'errors' => null,
        ], 404);
    }
}
