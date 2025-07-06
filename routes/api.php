<?php

use App\Http\Controllers\Api\Internal\PaymentController;
use App\Http\Controllers\Api\External\PaymentController as ExternalPaymentController;
use App\Http\Controllers\Api\Internal\UserController;
use App\Http\Middleware\VerifyHmac;
use Illuminate\Support\Facades\Route;
use Laravel\Octane\Octane;

// Апи для внешних запросов, используем octane + swoole
// Защищены HMAC
Route::prefix('external')/*->middleware(VerifyHmac::class)*/->group(function () {
    Route::prefix('payments')->group(function () {
        Route::post('/create', [ExternalPaymentController::class, 'create']);
    });

    Route::withoutMiddleware(VerifyHmac::class)->get('/health', function () {
        return response()->json([
            'pid' => getmypid(),
            'octane' => app()->bound(Octane::class),
            'server' => app()->bound(Octane::class)
                ? get_class(app(Octane::class))
                : null,
        ]);
    });
});

// Апи для внутренних запросов админки, используем FPM
// Защищены basic-auth на уровне nginx
Route::prefix('internal')->group(function () {
    Route::prefix('users')->group(function () {
        Route::get('/get', [UserController::class, 'get']);
    });

    Route::prefix('payments')->group(function () {
        Route::get('/get', [PaymentController::class, 'get']);
        Route::post('/update-status', [PaymentController::class, 'updateStatus']);
        Route::get('/export', [PaymentController::class, 'export']);
    });
});
