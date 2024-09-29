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

    public function store(MenuItemRequest $request): JsonResponse
    {
        $item = $this->menuItemRepository->create($request->validated());
        return response()->json([
            'data' => $item,
            'success' => true,
            'message' => 'Menu item created successfully.',
            'errors' => null
        ], 201);
    }

    public function update(MenuItemRequest $request, $id): JsonResponse
    {
        $item = $this->menuItemRepository->update($id, $request->validated());
        return response()->json([
            'data' => $item,
            'success' => true,
            'message' => 'Menu item updated successfully.',
            'errors' => null
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $this->menuItemRepository->delete($id);
        return response()->json([
            'data' => null,
            'success' => true,
            'message' => 'Menu item deleted successfully.',
            'errors' => null
        ], 204);
    }

    public function getAllByBusinessId($businessId): JsonResponse
    {
        $items = $this->menuItemRepository->getAllByBusinessId($businessId);
        return response()->json([
            'data' => $items,
            'success' => true,
            'message' => 'Menu items retrieved successfully.',
            'errors' => null
        ]);
    }

    public function getById($id): JsonResponse
    {
        $item = $this->menuItemRepository->getById($id);
        return response()->json([
            'data' => $item,
            'success' => true,
            'message' => 'Menu item retrieved successfully.',
            'errors' => null
        ]);
    }
}
