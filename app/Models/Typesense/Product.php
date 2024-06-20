<?php

namespace App\Models\Typesense;

use Laravel\Scout\EngineManager;
use Laravel\Scout\Engines\Engine;
use Laravel\Scout\Searchable;
use Typesense\LaravelTypesense\Interfaces\TypesenseDocument;

class Product extends \App\Models\Product implements TypesenseDocument
{
    use Searchable;

    protected $casts = [];

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
            'fields'     => $this->fields,
            'created_at' => $this->created_at->timestamp,
        ];
    }

    public function getCollectionSchema(): array
    {
        return [
            'name'                  => $this->searchableAs(),
            // 'enable_nested_fields'  => true,
            'default_sorting_field' => 'created_at',
            // 'token_separators'      => [':', '/', '.'],
            'fields'                => [
                // [
                //     'name' => '.*',
                //     'type' => 'auto',
                // ],
                [
                    'name' => 'title',
                    'type' => 'string',
                ],
                [
                    'name' => 'fields',
                    'type' => 'object',
                ],
                [
                    'name'  => '.*_facet',
                    'type'  => 'auto',
                    'facet' => true,
                ],
                [
                    'name' => 'url',
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
            'fields',
        ];
    }
}
