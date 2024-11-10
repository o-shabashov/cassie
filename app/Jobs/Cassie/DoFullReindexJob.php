<?php

namespace App\Jobs\Cassie;

use App\Auth;
use App\Exceptions\ShopifyProductException;
use App\GraphQL\ShopifyProduct;
use App\Models\Meilisearch\MeilisearchProduct;
use App\Models\Product;
use App\Models\Typesense\TypesenseProduct;
use Artisan;
use Exception;
use Gnikyt\BasicShopifyAPI\BasicShopifyAPI;
use Illuminate\Support\Str;
use Log;

class DoFullReindexJob extends BaseCassieHighQueueJob
{
    public function handle(): void
    {
        try {
            $api = app(BasicShopifyAPI::class, ['shop' => $this->shopifyUserDto->name, 'accessToken' => $this->shopifyUserDto->password]);
            Auth::loginUsingId($this->shopifyUserDto->cassie_id);

            // TODO iterate over all products
            // 1. Insert all products to the database
            collect(data_get(ShopifyProduct::get($api), 'body.container.data.products.nodes'))
                ->each(function ($product) {
                    Product::asTenant()->updateOrCreate(
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
            // Artisan::queue('mail:send', [
            //     'user' => 1, '--queue' => 'default'
            // ])->onConnection('redis')->onQueue('commands');

            // 2. Send all products to the main search engine
            Artisan::call('scout:flush', ['model' => MeilisearchProduct::class]);
            Artisan::call('scout:flush', ['model' => TypesenseProduct::class]);

            // 3. Send all products to the backup search engine
            MeilisearchProduct::asTenant()->get()->searchable();
            TypesenseProduct::asTenant()->get()->searchable();
        } catch (Exception $e) {
            if ($e instanceof ShopifyProductException) {
                $error = data_get($e->response->getDecodedBody(), 'errors');
            } else {
                $error = $e->getMessage();
            }

            Log::error("Failed to index products: $error");
        }
    }
}
