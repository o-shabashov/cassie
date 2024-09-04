<?php

namespace App\Jobs;

use App\Exceptions\ShopifyProductException;
use App\Lib\ProductIndex;
use App\Models\Meilisearch;
use App\Models\Product;
use App\Models\Typesense;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Shopify\Auth\Session as ShopifySession;

class DoFullReindexJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public ShopifySession $session) {}

    public function handle(): void
    {
        try {
            // 1. Insert all products to the database
            collect(data_get(ProductIndex::call($this->session), 'data.products.nodes'))
                ->each(function ($product) {
                    Product::updateOrCreate(
                        [
                            'shopify_id' => (int) Str::remove('gid://shopify/Product/', $product['id']),
                        ],
                        [
                            'title'      => $product['title'],
                            'fields'     => $product,
                            'url'        => $product['handle'],
                            'created_at' => $product['createdAt'],
                            'updated_at' => $product['updatedAt'],
                        ]
                    );
                });

            // TODO get user search engine settings
            // TODO flush and reindex in different queues - high for main and low for the backup

            // 2. Send all products to the main search engine
            Artisan::call('scout:flush', ['model' => Meilisearch\Product::class]);
            Artisan::call('scout:flush', ['model' => Typesense\Product::class]);

            // 3. Send all products to the backup search engine
            Meilisearch\Product::all()->searchable();
            Typesense\Product::all()->searchable();

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
