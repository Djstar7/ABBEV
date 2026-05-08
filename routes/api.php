<?php

use App\Http\Controllers\Api\MediaApiController;
use App\Http\Controllers\Api\SubscriptionPaymentController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/media', [MediaApiController::class, 'index']);
    Route::get('/media/featured', [MediaApiController::class, 'featured']);
    Route::get('/media/{slug}', [MediaApiController::class, 'show']);
    Route::get('/categories', [MediaApiController::class, 'categories']);
});

// Paiement des abonnements
Route::middleware('auth:sanctum')->prefix('subscription-payment')->group(function () {
    Route::post('/initiate', [SubscriptionPaymentController::class, 'initiate']);
    Route::post('/paypal/capture', [SubscriptionPaymentController::class, 'capturePayPal']);
    Route::get('/freemopay/status/{reference}', [SubscriptionPaymentController::class, 'checkFreeMoPayStatus']);
});

// Webhooks (sans authentification)
Route::post('/webhooks/freemopay', [SubscriptionPaymentController::class, 'freemopayWebhook']);
