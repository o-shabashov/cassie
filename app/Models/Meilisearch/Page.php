<?php

namespace App\Models\Meilisearch;

use Laravel\Scout\EngineManager;
use Laravel\Scout\Engines\Engine;
use Laravel\Scout\Searchable;

class Page extends \App\Models\Page
{
    use Searchable;

    public function searchableUsing(): Engine
    {
        return app(EngineManager::class)->engine('meilisearch');
    }

    public function toSearchableArray(): array
    {
        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'url'        => $this->url,
            'sections'   => $this->sections,
            'created_at' => $this->created_at,
        ];
    }
}
