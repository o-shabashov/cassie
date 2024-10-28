<?php

namespace App\Jobs\Cassie;

use App\Enums\SearchEngines;
use App\Models\ShopifyAdmin\ShopifyAdminUser;
use App\Models\User;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        $database                 = 'user_db_'.$user->id;
        $userDbConfig             = config('database.connections.user_db_template');
        $userDbConfig['database'] = $database;
        config(["database.connections.$database" => $userDbConfig]);

        try {
            DB::connection('user_db_template')->statement("CREATE DATABASE $database");
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }

        Artisan::call('migrate', [
            '--database'    => $database,
            '--path'        => 'database/migrations/user_db',
            '--schema-path' => 'database/schema/user_db_template-schema.sql',
        ]);

        ShopifyAdminUser::find($this->shopifyUserDto->id)->update([
            'cassie_id'           => $user->id,
            'cassie_access_token' => $user->createToken($this->shopifyUserDto->name)->plainTextToken,
        ]);
    }
}
