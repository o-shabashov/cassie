<?php

use App\Http\Controllers\IndexationController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Shopify\AuthController;
use App\Http\Controllers\Shopify\ProductsController;
use App\Http\Middleware\EnsureShopifySession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Shopify\Clients\HttpHeaders;
use Shopify\Exception\InvalidWebhookException;
use Shopify\Webhooks\Registry;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/search', [SearchController::class, 'search']);

/*
|--------------------------------------------------------------------------
| Shopify API routes
|--------------------------------------------------------------------------
*/
Route::middleware(EnsureShopifySession::class)->group(function () {
    Route::controller(ProductsController::class)->group(function () {
        Route::get('/products/count', 'count');
        Route::post('/products', 'storeMany');
    });
});

Route::controller(AuthController::class)->group(function () {
    Route::get('/auth', 'auth');
    Route::post('/auth/callback', 'callback');
});

Route::controller(IndexationController::class)->group(function () {
   Route::post('/full-reindex', 'fullReindex');
});

Route::post('/webhooks', function (Request $request) {
    try {
        $topic = $request->header(HttpHeaders::X_SHOPIFY_TOPIC, '');

        $response = Registry::process($request->header(), $request->getContent());
        if (! $response->isSuccess()) {
            Log::error("Failed to process '$topic' webhook: {$response->getErrorMessage()}");

            return response()->json(['message' => "Failed to process '$topic' webhook"], 500);
        }
    } catch (InvalidWebhookException $e) {
        Log::error("Got invalid webhook request for topic '$topic': {$e->getMessage()}");

        return response()->json(['message' => "Got invalid webhook request for topic '$topic'"], 401);
    } catch (Exception $e) {
        Log::error("Got an exception when handling '$topic' webhook: {$e->getMessage()}");

        return response()->json(['message' => "Got an exception when handling '$topic' webhook"], 500);
    }
});
