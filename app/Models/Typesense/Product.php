<?php

namespace App\Models\Typesense;

use Laravel\Scout\EngineManager;
use Laravel\Scout\Engines\Engine;
use Laravel\Scout\Searchable;
use Typesense\LaravelTypesense\Interfaces\TypesenseDocument;

class Product extends \App\Models\Product implements TypesenseDocument
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
            // FIXME For some reasons typesense does not accept an array, duh! Even if set 'auto' in the collection. So we will loose all keys here
            // 'fields'   => implode(' ', Arr::flatten($this->fields)),
            'fields'     => $this->fields,
            'created_at' => $this->created_at->timestamp,
        ];
    }

    public function getCollectionSchema(): array
    {
        return [
            'name'                  => $this->searchableAs(),
            'fields'                => [
                [
                    'name' => '.*',
                    'type' => 'auto',
                ],
                [
                    'name'  => '.*_facet',
                    'type'  => 'auto',
                    'facet' => true,
                ],
                [
                    'name'     => 'url',
                    'type'     => 'auto',
                    'index'    => false,
                    'optional' => true,
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
            'default_sorting_field' => 'created_at',
        ];
    }

    public function typesenseQueryBy(): array
    {
        return [
            'title',
            'fields',
        ];
    }
}
