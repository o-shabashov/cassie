<?php

namespace App\Models\Typesense;

use App\Models\Page;
use Illuminate\Support\Arr;
use Laravel\Scout\Searchable;
use Typesense\LaravelTypesense\Interfaces\TypesenseDocument;

class TypesensePage extends Page implements TypesenseDocument
{
    use Searchable;

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
            'fields' => [
                [
                    'name' => 'id',
                    'type' => 'string',
                ],
                [
                    'name' => 'title',
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
            ],
            'default_sorting_field' => 'created_at',
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
