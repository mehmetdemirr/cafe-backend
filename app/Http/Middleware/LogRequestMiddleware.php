<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\LogService;

class LogRequestMiddleware
{
    protected $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Loglama işlemi (istek bilgileri)
        $this->logService->logEvent(
            'Incoming Request',
            json_encode([
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
                'user_id' => optional($request->user())->id,
                'payload' => $request->all(),
            ])
        );

        return $next($request);
    }

    /**
     * After the response is sent back, you can log more information here.
     */
    public function terminate($request, $response)
    {
        // Yanıt sonrası loglama işlemi
        $this->logService->logEvent(
            'Outgoing Response',
            json_encode([
                'status' => $response->status(),
                'response' => $response->getContent(),
            ],)
        );
    }
}
