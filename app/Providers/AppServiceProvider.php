<?php

namespace App\Providers;

use App\Interfaces\BusinessEntryRepositoryInterface;
use App\Interfaces\BusinessRatingRepositoryInterface;
use App\Interfaces\BusinessRepositoryInterface;
use App\Interfaces\CampaignInterface;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\ComplaintRepositoryInterface;
use App\Interfaces\EventRepositoryInterface;
use App\Interfaces\LoyaltyPointRepositoryInterface;
use App\Interfaces\MatchRepositoryInterface;
use App\Interfaces\MenuCategoryRepositoryInterface;
use App\Interfaces\MenuItemRepositoryInterface;
use App\Interfaces\MessageRepositoryInterface;
use App\Interfaces\NotificationRepositoryInterface;
use App\Interfaces\OneSignalRepositoryInterface;
use App\Interfaces\ReportInterface;
use App\Interfaces\SupportMessageRepositoryInterface;
use App\Interfaces\UserProfileRepositoryInterface;
use App\Repositories\BusinessEntryRepository;
use App\Repositories\BusinessRatingRepository;
use App\Repositories\BusinessRepository;
use App\Repositories\CampaignRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ComplaintRepository;
use App\Repositories\EventRepository;
use App\Repositories\LoyaltyPointRepository;
use App\Repositories\MatchRepository;
use App\Repositories\MenuCategoryRepository;
use App\Repositories\MenuItemRepository;
use App\Repositories\MessageRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\OneSignalRepository;
use App\Repositories\ReportRepository;
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
        $this->app->bind(MessageRepositoryInterface::class, MessageRepository::class);
        $this->app->bind(ReportInterface::class, ReportRepository::class);
        $this->app->bind(BusinessEntryRepositoryInterface::class, BusinessEntryRepository::class);
        $this->app->bind(EventRepositoryInterface::class, EventRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->singleton(\App\Services\FileService::class, function ($app) {
            return new \App\Services\FileService();
        });
    }
}
