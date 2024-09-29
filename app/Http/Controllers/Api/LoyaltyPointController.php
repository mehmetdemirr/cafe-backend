<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BusinessRatingRequest;
use Illuminate\Http\Request;
use App\Http\Requests\LoyaltyPointRequest;
use App\Interfaces\BusinessRatingRepositoryInterface;
use Illuminate\Http\JsonResponse;

class LoyaltyPointController extends Controller
{
    protected $businessRatingRepository;

    public function __construct(BusinessRatingRepositoryInterface $businessRatingRepository)
    {
        $this->businessRatingRepository = $businessRatingRepository;
    }

    public function store(BusinessRatingRequest $request): JsonResponse
    {
        $rating = $this->businessRatingRepository->create($request->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'Rating created successfully.',
            'data' => $rating,
            'errors' => null,
        ], 201);
    }

    public function update(BusinessRatingRequest $request, $id): JsonResponse
    {
        $rating = $this->businessRatingRepository->update($id, $request->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'Rating updated successfully.',
            'data' => $rating,
            'errors' => null,
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $this->businessRatingRepository->delete($id);
        
        return response()->json([
            'success' => true,
            'message' => 'Rating deleted successfully.',
            'data' => null,
            'errors' => null,
        ], 204);
    }

    public function getAllByBusinessId($businessId): JsonResponse
    {
        $ratings = $this->businessRatingRepository->getAllByBusinessId($businessId);
        
        return response()->json([
            'success' => true,
            'message' => 'Ratings retrieved successfully.',
            'data' => $ratings,
            'errors' => null,
        ]);
    }

    public function getAverageRating($businessId): JsonResponse
    {
        $average = $this->businessRatingRepository->getAverageRating($businessId);
        
        return response()->json([
            'success' => true,
            'message' => 'Average rating retrieved successfully.',
            'data' => ['average_rating' => $average],
            'errors' => null,
        ]);
    }
}
