<?php

namespace App\Models\Traits;

use App\Auth;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Model
 */
trait HasTenant
{
    public static function asTenant(?User $user = null): Builder
    {
        $user = $user ?? Auth::user();

        if (app()->environment('testing')) {
            $database = 'user_db_testing';
        } else {
            $database = 'user_db_'.$user->id;
        }

        $userDbConfig = config('database.connections.user_db_template');
        $userDbConfig['database'] = $database;
        config(["database.connections.$database" => $userDbConfig]);

        return self::on($database);
    }
}
