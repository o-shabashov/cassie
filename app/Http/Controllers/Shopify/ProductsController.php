<?php

namespace App\Http\Controllers\Shopify;

use App\Exceptions\ShopifyProductCreatorException;
use App\Http\Controllers\Controller;
use App\Lib\ProductCreator;
use App\Models\Product;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Shopify\Auth\Session as AuthSession;
use Shopify\Clients\Rest;

class ProductsController extends Controller
{
    public function count(Request $request): \Illuminate\Foundation\Application|Response|Application|ResponseFactory
    {
        $this->authorize('viewAny', Product::class);

        /** @var AuthSession $session */
        $session = $request->get('shopifySession'); // Provided by the shopify.auth middleware, guaranteed to be active

        $client = new Rest($session->getShop(), $session->getAccessToken());
        $result = $client->get('products/count');

        return response($result->getDecodedBody());
    }

    public function storeMany(Request $request): JsonResponse
    {
        $this->authorize('create', Product::class);
        /** @var AuthSession $session */
        $session = $request->get('shopifySession'); // Provided by the shopify.auth middleware, guaranteed to be active

        try {
            ProductCreator::call($session, 5);
            $success = true;
            $code    = 200;
            $error   = null;
        } catch (Exception $e) {
            $success = false;

            if ($e instanceof ShopifyProductCreatorException) {
                $code  = $e->response->getStatusCode();
                $error = $e->response->getDecodedBody();
                if (array_key_exists('errors', $error)) {
                    $error = $error['errors'];
                }
            } else {
                $code  = 500;
                $error = $e->getMessage();
            }

            Log::error("Failed to create products: $error");
        } finally {
            return response()->json(['success' => $success, 'error' => $error], $code);
        }
    }
}
