<?php

namespace App\Models\Typesense;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Scout\EngineManager;
use Laravel\Scout\Engines\Engine;
use Laravel\Scout\Searchable;
use Typesense\LaravelTypesense\Interfaces\TypesenseDocument;

class TypesenseProduct extends \App\Models\Product implements TypesenseDocument
{
    use Searchable, HasFactory;

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
            'enable_nested_fields'  => true,
            'default_sorting_field' => 'created_at',
            'token_separators'      => [':', '/', '.'],
            'fields'                => [
                [
                    'name' => '.*',
                    'type' => 'auto',
                ],
                [
                    'name' => 'created_at',
                    'type' => 'int64',
                ],
                [
                    'name'  => '.*_facet',
                    'type'  => 'auto',
                    'facet' => true,
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
