<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MenuCategoryRequest;
use App\Interfaces\MenuCategoryRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MenuCategoryController extends Controller
{
    protected $menuCategoryRepository;

    public function __construct(MenuCategoryRepositoryInterface $menuCategoryRepository)
    {
        $this->menuCategoryRepository = $menuCategoryRepository;
    }

    public function store(MenuCategoryRequest $request): JsonResponse
    {
        $category = $this->menuCategoryRepository->create($request->validated());

        return response()->json([
            'data' => $category,
            'success' => true,
            'message' => 'Kategori başarıyla oluşturuldu.',
            'errors' => null,
        ], 201);
    }

    public function update(MenuCategoryRequest $request, $id): JsonResponse
    {
        $category = $this->menuCategoryRepository->update($id, $request->validated());

        return response()->json([
            'data' => $category,
            'success' => true,
            'message' => 'Kategori başarıyla güncellendi.',
            'errors' => null,
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $this->menuCategoryRepository->delete($id);

        return response()->json([
            'data' => null,
            'success' => true,
            'message' => 'Kategori başarıyla silindi.',
            'errors' => null,
        ], 204);
    }

    public function index($businessId): JsonResponse
    {
        $categories = $this->menuCategoryRepository->getAllByBusinessId($businessId);

        return response()->json([
            'data' => $categories,
            'success' => true,
            'message' => 'Kategoriler başarıyla alındı.',
            'errors' => null,
        ]);
    }

    public function show($id): JsonResponse
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
