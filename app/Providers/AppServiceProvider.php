<?php

namespace App\Providers;

use App\Interfaces\BusinessRatingRepositoryInterface;
use App\Interfaces\BusinessRepositoryInterface;
use App\Interfaces\CampaignInterface;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\ComplaintRepositoryInterface;
use App\Interfaces\LoyaltyPointRepositoryInterface;
use App\Interfaces\MatchRepositoryInterface;
use App\Interfaces\MenuCategoryRepositoryInterface;
use App\Interfaces\MenuItemRepositoryInterface;
use App\Interfaces\NotificationRepositoryInterface;
use App\Interfaces\SupportMessageRepositoryInterface;
use App\Interfaces\UserProfileRepositoryInterface;
use App\Repositories\BusinessRatingRepository;
use App\Repositories\BusinessRepository;
use App\Repositories\CampaignRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ComplaintRepository;
use App\Repositories\LoyaltyPointRepository;
use App\Repositories\MatchRepository;
use App\Repositories\MenuCategoryRepository;
use App\Repositories\MenuItemRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\SupportMessageRepository;
use App\Repositories\UserProfileRepository;
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
        $this->app->bind(MenuItemRepositoryInterface::class, MenuItemRepository::class);
        $this->app->bind(NotificationRepositoryInterface::class, NotificationRepository::class);
        $this->app->bind(MatchRepositoryInterface::class, MatchRepository::class);
        $this->app->bind(SupportMessageRepositoryInterface::class, SupportMessageRepository::class);
        $this->app->bind(ComplaintRepositoryInterface::class, ComplaintRepository::class);
        $this->app->bind(UserProfileRepositoryInterface::class, UserProfileRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
