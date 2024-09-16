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

    public function __construct(public User $shopifyAdminUser)
    {
    }

    public function handle(): void
    {
        $user = User::updateOrCreate(
            ['name' => $this->shopifyAdminUser->name],
            [
                'email'                => $this->shopifyAdminUser->email,
                'email_verified_at'    => $this->shopifyAdminUser->email_verified_at,
                'shopify_access_token' => $this->shopifyAdminUser->password,
                'password'             => $this->shopifyAdminUser->password,
            ]
        );

        $user->tokens()->delete();

        ShopifyAdminUser::find($this->shopifyAdminUser->id)->update([
            'cassie_id'           => $user->id,
            'cassie_access_token' => $user->createToken($this->shopifyAdminUser->name)->plainTextToken,
        ]);
    }
}
