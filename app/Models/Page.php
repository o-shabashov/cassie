<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Page extends Model
{
    use SoftDeletes, HasFactory, Searchable;

    protected $fillable = [
        'title',
        'url',
        'sections',
    ];

    protected $casts = [
        'sections' => 'array',
    ];

    protected $hidden = [
        'searchable',
    ];

    public function toSearchableArray(): array
    {
        return [
            'id'         => (string) $this->id,
            'title'      => $this->title,
            'sections'   => $this->sections,
            'created_at' => $this->created_at->timestamp,
        ];
    }

    /**
     * The Typesense schema to be created.
     */
    public function getCollectionSchema(): array
    {
        return [
            'name'                  => $this->searchableAs(),
            'fields'                => [
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
                    'type' => 'auto',
                ],
                [
                    'name' => 'created_at',
                    'type' => 'int64',
                ],
            ],
            'default_sorting_field' => 'created_at',
        ];
    }

    /**
     * The fields to be queried against
     * @see https://typesense.org/docs/0.25.0/api/search.html
     */
    public function typesenseQueryBy(): array
    {
        return [
            'title',
            'sections'
        ];
    }
}
