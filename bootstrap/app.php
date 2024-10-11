<?php

use App\Http\Middleware\LogRequestMiddleware;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        // channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->statefulApi();
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'log' => LogRequestMiddleware::class,
        ]);
    })
    ->withBroadcasting(
        __DIR__.'/../routes/channels.php',
        ['prefix' => 'api', 'middleware' => ['api', 'auth:sanctum']],
    )
    ->withExceptions(function (Exceptions $exceptions) {
        // 401 Unauthorized durumunu özelleştiriyoruz
        $exceptions->map(AuthenticationException::class, function (AuthenticationException $exception) {
            return new class($exception) extends \Exception {
                public function render($request)
                {
                    return response()->json([
                        'success' => false,
                        'data' => null,
                        'message' => 'Yetkisiz işlem.',
                        'errors' => 'Yetkisiz işlem.',
                    ], 401);
                }
            };
        });

        // 403 Forbidden durumunu özelleştiriyoruz
        $exceptions->map(\Illuminate\Auth\Access\AuthorizationException::class, function ($exception) {
            return new class($exception) extends \Exception {
                public function render($request)
                {
                    return response()->json([
                        'success' => false,
                        'data' => null,
                        'message' => 'Bu işlem için yetkiniz yok.',
                        'errors' => 'Yetkisiz erişim.',
                    ], 403);
                }
            };
        });

        // ThrottleRequestsException için özelleştirme
        $exceptions->map(\Illuminate\Http\Exceptions\ThrottleRequestsException::class, function ($exception) {
            return new class($exception) extends \Exception {
                public function render($request)
                {
                    return response()->json([
                        'success' => false,
                        'data' => null,
                        'message' => 'Çok fazla istek yaptınız. Lütfen birkaç dakika sonra tekrar deneyin.',
                        'errors' => 'Çok fazla istek yaptınız.',
                    ], 400);
                }
            };
        });
    })->create();
