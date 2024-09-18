<?php

namespace App\Providers;

use App\Models\User;
use Gnikyt\BasicShopifyAPI\BasicShopifyAPI;
use Gnikyt\BasicShopifyAPI\Options;
use Gnikyt\BasicShopifyAPI\Session;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class ShopifyApiServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->singleton(BasicShopifyAPI::class, function () {
            /** @var User $user */
            $user = Auth::user();

            $options = new Options();
            $options->setVersion(config('services.shopify.api_version'));

            $api = new BasicShopifyAPI($options);
            $api->setSession(new Session($user->name, $user->shopify_access_token));

            return $api;
        });
    }

    public function provides(): array
    {
        return [BasicShopifyAPI::class];
    }
}
