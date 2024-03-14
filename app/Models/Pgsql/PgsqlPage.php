<?php

namespace App\Models\Pgsql;

use App\Models\Page;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class PgsqlPage extends Page
{
    public static function search($query = ''): Builder
    {
        $operator = 'to_tsquery';
        if (Str::contains($query, ' ')) {
            $operator = 'phraseto_tsquery';
        }

        /**
         * PHPStorm is going crazy - thought it's will be Page model
         * @noinspection PhpIncompatibleReturnTypeInspection
         */
        return Page::whereRaw("title % '$query'")
                   ->orWhereRaw("searchable @@ $operator('english', '$query:*')");
    }
}
