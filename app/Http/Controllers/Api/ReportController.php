<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\ReportInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $reportRepository;

    public function __construct(ReportInterface $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    // Function 1: Get views by category for a specific business
    public function getCategoryViews(Request $request): JsonResponse
    {
        $businessId = $request->user()->business->id;

        // Retrieve the category views data from the repository
        $categoryViews = $this->reportRepository->getCategoryViews($businessId);

        return response()->json([
            'success' => true,
            'data' => $categoryViews,
            'message' => 'Category views fetched successfully.',
            'errors' => null,
        ], 200);
    }

    // Function 2: Get daily, weekly, monthly, and total views for a category
    public function getCategoryViewStats(Request $request): JsonResponse
    {
        $categoryId = $request->input('category_id');

        // Retrieve the view statistics from the repository
        $viewStats = $this->reportRepository->getCategoryViewStats($categoryId);

        return response()->json([
            'success' => true,
            'data' => $viewStats,
            'message' => 'Category view statistics fetched successfully.',
            'errors' => null,
        ], 200);
    }
}
