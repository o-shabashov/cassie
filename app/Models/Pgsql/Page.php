<?php

namespace App\Models\Pgsql;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Page extends \App\Models\Page
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
