<?php

namespace App\Repositories;

use App\Interfaces\ReportInterface;
use App\Models\MenuCategory;
use App\Models\MenuCategoryView;
use Carbon\Carbon;

class ReportRepository implements ReportInterface
{
    // Function 1: Get views by category for a specific business
    public function getCategoryViews(int $businessId): array
    {
        // Fetch the categories with their view counts
        $categories = MenuCategory::where('business_id', $businessId)
            ->select('name', 'views')
            ->get();

        // Format the data as required
        $result = [];
        foreach ($categories as $category) {
            $result[] = [
                $category->name => $category->views,
            ];
        }

        return $result;
    }

    // Function 2: Get daily, weekly, monthly, and total views for a category
    public function getCategoryViewStats(): array
    {
        // Calculate view statistics
        $dailyViews = MenuCategoryView::whereDate('viewed_at', Carbon::today())
            ->count();

        $weeklyViews = MenuCategoryView::whereBetween('viewed_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->count();

        $monthlyViews = MenuCategoryView::whereMonth('viewed_at', Carbon::now()->month)
            ->count();

        $totalViews = MenuCategoryView::count();

        // Return the formatted data
        return [
            'daily' => $dailyViews,
            'weekly' => $weeklyViews,
            'monthly' => $monthlyViews,
            'total' => $totalViews,
        ];
    }
}
