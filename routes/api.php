<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\BusinessController;
use App\Http\Controllers\Api\BusinessRatingController;
use App\Http\Controllers\Api\CampaignController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\LoyaltyPointController;
use App\Http\Controllers\Api\MenuCategoryController;
use App\Http\Controllers\Api\MenuItemController;
use App\Http\Controllers\Api\UserController;
use App\Models\BusinessRating;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(["log"])->group(function () {
    RateLimiter::for('api', function (Request $request) {
        return Limit::perMinute(35)->by(optional($request->user())->id ?: $request->ip());
    });
    
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('user', [UserController::class, 'user']);
        Route::put('user/update', [UserController::class, 'update']);

        Route::group(['prefix' => 'categories'], function () {
            Route::get('/', [CategoryController::class, 'index']); 
            Route::get('/{id}', [CategoryController::class, 'show']); 
            Route::post('/', [CategoryController::class, 'store']); 
            Route::put('/{id}', [CategoryController::class, 'update']); 
            Route::delete('/{id}', [CategoryController::class, 'destroy']); 
        });

        Route::group(['prefix' => 'user/favorite-categories'], function () {
            Route::get('/', [CategoryController::class, 'userFavorites']); 
            Route::post('/{categoryId}', [CategoryController::class, 'addToFavorites']); 
            Route::delete('/{categoryId}', [CategoryController::class, 'removeFromFavorites']);
        });

        Route::group(['prefix' => 'business'], function () {
            // Kullanıcının favori işletmeleri (businesses)
            Route::get('/favorites', [BusinessController::class, 'favoriteBusinesses']);
            Route::post('{id}/favorite', [BusinessController::class, 'addToFavorites']);
            Route::delete('{id}/favorite', [BusinessController::class, 'removeFromFavorites']);
            Route::post('{id}/rate', [BusinessController::class, 'rate']);
            // İşletme puanlarını listeleme
            Route::get('{id}/ratings', [BusinessController::class, 'ratings']);
            Route::post('/', [BusinessController::class, 'store']);
            Route::put('{id}', [BusinessController::class, 'update']);
            Route::delete('/{id}', [BusinessController::class, 'destroy']);
            Route::get('/{id}', [BusinessController::class, 'show']);
            Route::get('/', [BusinessController::class, 'index']);
        });

        Route::prefix('campaigns')->group(function () {
            Route::post('/', [CampaignController::class, 'create'])->name('campaign.create');
            Route::put('/{campaignId}', [CampaignController::class, 'update'])->name('campaign.update');
            Route::delete('/{campaignId}', [CampaignController::class, 'delete'])->name('campaign.delete');
            
            // Kullanıcı kampanya katılımı
            Route::post('/{campaignId}/join/{userId}', [CampaignController::class, 'joinCampaign'])->name('campaign.join');
            Route::post('/{campaignId}/leave/{userId}', [CampaignController::class, 'leaveCampaign'])->name('campaign.leave');
            
            // Kullanıcının kampanya katılım durumu
            Route::get('/{campaignId}/is-joined/{userId}', [CampaignController::class, 'isUserJoined'])->name('campaign.isJoined');
        });

        Route::prefix('loyalty-points')->group(function () {
            Route::get('/', [LoyaltyPointController::class, 'index']); 
            Route::get('/user/{userId}', [LoyaltyPointController::class, 'findByUserId']); // Kullanıcı ID'sine göre loyalty points
            Route::post('/', [LoyaltyPointController::class, 'store']);
            Route::put('/{id}', [LoyaltyPointController::class, 'update']);
            Route::delete('/{id}', [LoyaltyPointController::class, 'destroy']); 
        });

        Route::prefix('business-ratings')->group(function () {
            Route::post('/', [BusinessRatingController::class, 'store']);
            Route::put('/{id}', [BusinessRatingController::class, 'update']);
            Route::delete('/{id}', [BusinessRatingController::class, 'destroy']);
            Route::get('/business/{businessId}', [BusinessRatingController::class, 'getAllByBusinessId']);
            Route::get('/business/{businessId}/average', [BusinessRatingController::class, 'getAverageRating']);
        });

        // postman

        Route::prefix('menu-categories')->group(function () {
            Route::get('/business/{businessId}', [MenuCategoryController::class, 'getCategoriesForCustomer']);
            Route::get('/', [MenuCategoryController::class, 'index']);
            Route::post('/', [MenuCategoryController::class, 'store']);
            Route::put('/{id}', [MenuCategoryController::class, 'update']);
            Route::delete('/{id}', [MenuCategoryController::class, 'destroy']);
            Route::get('/{id}', [MenuCategoryController::class, 'show']);
        });
        

        Route::prefix('menu-items')->group(function () {
            Route::get('/', [MenuItemController::class, 'index']); 
            Route::get('/{id}', [MenuItemController::class, 'show']); 
            Route::post('/', [MenuItemController::class, 'store']);
            Route::put('/{id}', [MenuItemController::class, 'update']); 
            Route::delete('/{id}', [MenuItemController::class, 'destroy']);
        });

        
        
        // //admin route
        // Route::middleware(["role:admin"])->group(function () {
            
        // });
        // //company route
        // Route::middleware(["role:company"])->group(function () {
            
        // });
        // //user route
        // Route::middleware(["role:user"])->group(function () {
           
        // }); 
    });
    
    Route::middleware(["throttle:30,1"])->group(function () {
        Route::post('auth/login', [AuthController::class, 'login']);
        Route::post('auth/register', [AuthController::class, 'register']);
    
        Route::post('auth/password-forgot',[ForgotPasswordController::class,'forgotPassword']);
        Route::post('auth/password-reset',[ResetPasswordController::class,'resetPassword']);
    });
});
