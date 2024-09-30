<?php

namespace App\Providers;

use App\Interfaces\BusinessRatingRepositoryInterface;
use App\Interfaces\BusinessRepositoryInterface;
use App\Interfaces\CampaignInterface;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\LoyaltyPointRepositoryInterface;
use App\Interfaces\MenuCategoryRepositoryInterface;
use App\Repositories\BusinessRatingRepository;
use App\Repositories\BusinessRepository;
use App\Repositories\CampaignRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\LoyaltyPointRepository;
use App\Repositories\MenuCategoryRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(BusinessRepositoryInterface::class, BusinessRepository::class);
        $this->app->bind(BusinessRatingRepositoryInterface::class, BusinessRatingRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(CampaignInterface::class, CampaignRepository::class);
        $this->app->bind(LoyaltyPointRepositoryInterface::class, LoyaltyPointRepository::class);
        $this->app->bind(BusinessRatingRepositoryInterface::class, BusinessRatingRepository::class);
        $this->app->bind(MenuCategoryRepositoryInterface::class, MenuCategoryRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
