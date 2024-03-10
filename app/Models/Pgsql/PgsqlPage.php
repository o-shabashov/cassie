<?php

namespace App\Models\Pgsql;

use App\Models\Page;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Attributes\SearchUsingPrefix;
use Laravel\Scout\EngineManager;
use Laravel\Scout\Engines\Engine;
use Laravel\Scout\Searchable;

class PgsqlPage extends Page
{
    use Searchable;

    protected $table = 'pages';

    public function searchableUsing(): Engine
    {
        return app(EngineManager::class)->engine('database');
    }

    #[SearchUsingPrefix(['id', 'title', 'url'])]
    #[SearchUsingFullText(['sections'])]
    public function toSearchableArray(): array
    {
        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'url'        => $this->url,
            'sections'   => $this->sections,
        ];
    }
}
