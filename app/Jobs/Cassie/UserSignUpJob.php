<?php

namespace App\Jobs\Cassie;

use App\Enums\SearchEngines;
use App\Models\ShopifyAdmin\ShopifyAdminUser;
use App\Models\User;
use Illuminate\Support\Arr;

class UserSignUpJob extends BaseCassieHighQueueJob
{
    public function handle(): void
    {
        $user = User::updateOrCreate(
            ['name' => $this->shopifyUserDto->name],
            [
                'shopify_access_token' => $this->shopifyUserDto->password,
                'settings'             => User::generateSettings(),
            ]
        );

        $user->current_engine ?? Arr::random(SearchEngines::active());
        $user->save();

        $user->tokens()?->delete();

        ShopifyAdminUser::find($this->shopifyUserDto->id)->update([
            'cassie_id'           => $user->id,
            'cassie_access_token' => $user->createToken($this->shopifyUserDto->name)->plainTextToken,
        ]);
    }
}
