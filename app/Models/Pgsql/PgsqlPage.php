<?php

namespace App\Models\Pgsql;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

/**
 * @inheritdoc
 */
class PgsqlPage extends \App\Models\Page
{
    public static function search($query = ''): Builder
    {
        $operator = 'to_tsquery';
        if (Str::contains($query, ' ')) {
            $operator = 'phraseto_tsquery';
        }

        return self::query()->whereRaw("title % '$query'")
            ->orWhereRaw("searchable @@ $operator('english', '$query:*')");
    }
}
