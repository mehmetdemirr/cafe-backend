<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoyaltyPointRequest;
use App\Http\Requests\UpdateLoyaltyPointRequest;
use App\Interfaces\LoyaltyPointRepositoryInterface;
use Illuminate\Http\JsonResponse;

class LoyaltyPointController extends Controller
{
    protected $loyaltyPointRepository;

    public function __construct(LoyaltyPointRepositoryInterface $loyaltyPointRepository)
    {
        $this->loyaltyPointRepository = $loyaltyPointRepository;
    }

    // Index method to get all loyalty points
    public function index(): JsonResponse
    {
        $loyaltyPoints = $this->loyaltyPointRepository->all();
        
        return response()->json([
            'success' => true,
            'message' => 'Loyalty points retrieved successfully.',
            'data' => $loyaltyPoints,
            'errors' => null,
        ]);
    }

    // Store new loyalty point
    public function store(LoyaltyPointRequest $request): JsonResponse
    {
        $loyaltyPoint = $this->loyaltyPointRepository->create($request->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'Loyalty point created successfully.',
            'data' => $loyaltyPoint,
            'errors' => null,
        ], 201);
    }

    // Update existing loyalty point
    public function update(UpdateLoyaltyPointRequest $request, $id): JsonResponse
    {
        $isUpdated = $this->loyaltyPointRepository->update($id, $request->validated());
        
        if ($isUpdated) {
            return response()->json([
                'success' => true,
                'message' => 'Loyalty point updated successfully.',
                'data' => $isUpdated,
                'errors' => null,
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Loyalty point not found or not updated.',
            'data' => null,
            'errors' => 'Loyalty point could not be updated.',
        ], 404);
    }

    // Delete loyalty point
    public function destroy($id): JsonResponse
    {
        $isDeleted = $this->loyaltyPointRepository->delete($id);
        
        if ($isDeleted) {
            return response()->json([
                'success' => true,
                'message' => 'Loyalty point deleted successfully.',
                'data' => null,
                'errors' => null,
            ], 200);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Loyalty point not found.',
            'data' => null,
            'errors' => 'Loyalty point could not be deleted.',
        ], 404);
    }

    // Get loyalty points by user ID
    public function findByUserId($userId): JsonResponse
    {
        $loyaltyPoints = $this->loyaltyPointRepository->findByUserId($userId);
        
        return response()->json([
            'success' => true,
            'message' => 'Loyalty points retrieved for user.',
            'data' => $loyaltyPoints,
            'errors' => null,
        ]);
    }
}
