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

    public function __construct(public User $shopifyUser)
    {
    }

    public function handle(): void
    {
        $user = User::updateOrCreate(
            ['name' => $this->shopifyUser->name],
            [
                'email'                => $this->shopifyUser->email,
                'email_verified_at'    => $this->shopifyUser->email_verified_at,
                'shopify_access_token' => $this->shopifyUser->password,
                'password'             => $this->shopifyUser->password,
            ]
        );

        $user->tokens()->delete();

        ShopifyAdminUser::find($this->shopifyUser->id)->update([
            'cassie_id'           => $user->id,
            'cassie_access_token' => $user->createToken($this->shopifyUser->name)->plainTextToken,
        ]);
    }
}
