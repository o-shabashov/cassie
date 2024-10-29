<?php

namespace App\Models\Meilisearch;

use App\Auth;
use App\Models\User;
use Laravel\Scout\EngineManager;
use Laravel\Scout\Engines\Engine;
use Laravel\Scout\Searchable;

class MeilisearchProduct extends \App\Models\Product
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
            'fields'     => $this->fields,
            'created_at' => $this->created_at,
        ];
    }

    public function searchableAs(?User $user = null): string
    {
        return sprintf('products_%s', Auth::user() ? Auth::user()->id : $user->id);
    }
}
