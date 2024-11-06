<?php

namespace App\Providers;

use App\Auth;
use Gnikyt\BasicShopifyAPI\BasicShopifyAPI;
use Gnikyt\BasicShopifyAPI\Options;
use Gnikyt\BasicShopifyAPI\Session;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class ShopifyApiServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->singleton(BasicShopifyAPI::class, function (Application $app, array $params) {
            $options = new Options;
            $options->setVersion(config('services.shopify.api_version'));

            $api = new BasicShopifyAPI($options);

            if ($params) {
                $api->setSession(new Session(...$params));
            } else {
                $api->setSession(new Session(shop: Auth::user()->name, accessToken: Auth::user()->shopify_access_token));
            }

            return $api;
        });
    }

    public function provides(): array
    {
        return [BasicShopifyAPI::class];
    }
}
