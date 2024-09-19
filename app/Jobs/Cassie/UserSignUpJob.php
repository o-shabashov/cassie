<?php

namespace App\Jobs\Cassie;

use App\Models\ShopifyAdmin\ShopifyAdminUser;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class UserSignUpJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    // TODO use SimpleDTO here https://wendell-adriel.gitbook.io/laravel-validated-dto/basics/simple-dtos
    public function __construct(
        public int $shopifyUserId,
        public string $shopName,
        public string $shopifyAccessToken
    ) {
    }

    public function handle(): void
    {
        $user = User::updateOrCreate(
            ['name' => $this->shopName],
            [
                'shopify_access_token' => $this->shopifyAccessToken,
            ]
        );

        $user->tokens()->delete();

        ShopifyAdminUser::find($this->shopifyUserId)->update([
            'cassie_id'           => $user->id,
            'cassie_access_token' => $user->createToken($this->shopName)->plainTextToken,
        ]);
    }
}
