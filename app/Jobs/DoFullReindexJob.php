<?php

namespace App\Jobs;

use App\Exceptions\ShopifyProductException;
use App\Lib\ProductIndex;
use App\Models\Product;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Shopify\Auth\Session as ShopifySession;

class DoFullReindexJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public ShopifySession $session)
    {
    }

    public function handle(): void
    {
        try {
            collect(data_get(ProductIndex::call($this->session), 'data.products.nodes'))
                ->each(function ($product) {
                    Product::updateOrCreate(
                        [
                            'shopify_id' => (int) Str::remove('gid://shopify/Product/', $product['id'])
                        ],
                        [
                            'title' => $product['title'],
                            'fields' => $product,
                            'url' => $product['handle'],
                            'created_at' => $product['createdAt'],
                            'updated_at' => $product['updatedAt'],
                        ]
                    );
            });
        } catch (Exception $e) {
            if ($e instanceof ShopifyProductException) {
                $error = data_get($$e->response->getDecodedBody(), 'errors');
            } else {
                $error = $e->getMessage();
            }

            Log::error("Failed to index products: $error");
        }
    }
}
