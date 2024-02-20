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
}
