<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Page;
use App\Models\Product;
use App\Policies\PagePolicy;
use App\Policies\ProductsPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
        Page::class    => PagePolicy::class,
        Product::class => ProductsPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
