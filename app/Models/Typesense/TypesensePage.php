<?php

namespace App\Models\Typesense;

use Illuminate\Support\Arr;
use Laravel\Scout\EngineManager;
use Laravel\Scout\Engines\Engine;
use Laravel\Scout\Searchable;
use Typesense\LaravelTypesense\Interfaces\TypesenseDocument;

class TypesensePage extends \App\Models\Page implements TypesenseDocument
{
    use Searchable;

    public function searchableUsing(): Engine
    {
        return app(EngineManager::class)->engine('typesense');
    }

    public function toSearchableArray(): array
    {
        return [
            'id'         => (string) $this->id,
            'title'      => $this->title,
            'url'        => $this->url,
            'sections'   => implode(' ', Arr::flatten($this->sections)),
            'created_at' => $this->created_at->timestamp,
        ];
    }

    public function getCollectionSchema(): array
    {
        return [
            'name'   => $this->searchableAs(),
            'default_sorting_field' => 'created_at',
            'fields' => [
                [
                    'name' => 'title',
                    'type' => 'string',
                ],
                [
                    'name' => 'url',
                    'type' => 'string',
                ],
                [
                    'name' => 'sections',
                    'type' => 'string',
                ],
                [
                    'name' => 'created_at',
                    'type' => 'int64',
                ],
                [
                    'name'     => '__soft_deleted',
                    'type'     => 'int32',
                    'optional' => true,
                ],
            ],
        ];
    }

    public function typesenseQueryBy(): array
    {
        return [
            'title',
            'sections',
        ];
    }
}
