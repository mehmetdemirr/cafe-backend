<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MenuCategoryRequest;
use App\Interfaces\MenuCategoryRepositoryInterface;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class MenuCategoryController extends Controller
{
    protected $menuCategoryRepository;

    public function __construct(MenuCategoryRepositoryInterface $menuCategoryRepository)
    {
        $this->menuCategoryRepository = $menuCategoryRepository;
    }

     // Business user retrieves all categories for their business
    public function index(Request $request): JsonResponse
    {
        Gate::authorize('viewAny', MenuCategory::class);
        $businessId = $request->user()->business->id; // Get the business ID
        $categories = $this->menuCategoryRepository->getAllByBusinessId($businessId);

        // If $categories is empty
        if (empty($categories)) {
            return response()->json([
                'data' => [],
                'success' => true,
                'message' => 'Bu işletmeye ait kategori bulunamadı.',
                'errors' => null,
            ]);
        }

        return response()->json([
            'data' => $categories,
            'success' => true,
            'message' => 'Kategoriler başarıyla alındı.',
            'errors' => null,
        ]);
    }

    // Customer retrieves categories by business_id (QR scan)
    public function getCategoriesForCustomer($businessId): JsonResponse
    {
        $categories = $this->menuCategoryRepository->getCategoriesForCustomerByBusinessId($businessId);

        return response()->json([
            'data' => $categories,
            'success' => true,
            'message' => 'Kategoriler başarıyla alındı.',
            'errors' => null,
        ]);
    }

    // Business user creates a new category
    public function store(MenuCategoryRequest $request): JsonResponse
    {
        $businessId = $request->user()->business->id;
        $data = array_merge($request->validated(), ['business_id' => $businessId]);
        
        $category = $this->menuCategoryRepository->create($data);

        return response()->json([
            'data' => $category,
            'success' => true,
            'message' => 'Kategori başarıyla oluşturuldu.',
            'errors' => null,
        ], 201);
    }

    // Business user updates a category
    public function update(MenuCategoryRequest $request, $id): JsonResponse
    {
        $category = $this->menuCategoryRepository->findById($id);

        if (!$category) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => 'Geçersiz istek.',
                'errors' => 'Kategori bulunamadı.',
            ], 400); // Bad Request
        }
        Gate::authorize('update', $category);
        $updated = $this->menuCategoryRepository->update($id, $request->validated());

        if (!$updated) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => 'Kategori güncellenirken bir hata oluştu.',
                'errors' => 'Kategori bulunamadı veya güncellenemedi.',
            ], 404);
        }

        return response()->json([
            'data' => $this->menuCategoryRepository->findById($id),
            'success' => true,
            'message' => 'Kategori başarıyla güncellendi.',
            'errors' => null,
        ]);
    }

    // Business user deletes a category
    public function destroy($id): JsonResponse
    {
        $category = $this->menuCategoryRepository->findById($id);

        if (!$category) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => 'Kategori silinirken bir hata oluştu.',
                'errors' => 'Kategori bulunamadı.',
            ], 404);
        }

        Gate::authorize('delete', $category);
        $this->menuCategoryRepository->delete($id);

        return response()->json([
            'data' => null,
            'success' => true,
            'message' => 'Kategori başarıyla silindi.',
            'errors' => null,
        ], 200);
    }

    // Show a single category
    public function show(int $id): JsonResponse
    {
        $category = $this->menuCategoryRepository->findById($id);

        if (!$category) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => 'Kategori bulunamadı.',
                'errors' => 'Kategori bulunamadı.',
            ], 404);
        }

        return response()->json([
            'data' => $category,
            'success' => true,
            'message' => 'Kategori başarıyla alındı.',
            'errors' => null,
        ]);
    }
}