<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\UserController;
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
        
        //admin route
        Route::middleware(["role:admin"])->group(function () {
            
        });
    
        //company route
        Route::middleware(["role:company"])->group(function () {
            
        });
    
        //user route
        Route::middleware(["role:user"])->group(function () {
           
        });
    
    
    });
    
    Route::middleware(["throttle:30,1"])->group(function () {
        Route::post('auth/login', [AuthController::class, 'login']);
        Route::post('auth/register', [AuthController::class, 'register']);
    
        Route::post('auth/password-forgot',[ForgotPasswordController::class,'forgotPassword']);
        Route::post('auth/password-reset',[ResetPasswordController::class,'resetPassword']);
    });
});
