<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
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
            'sections'   => implode(' ', Arr::flatten($this->sections)),
        ];
    }

    /**
     * pgsql driver only
     */
    public function searchableOptions(): array
    {
        return [
            // You may wish to change the default name of the column
            // that holds parsed documents
//            'column' => 'indexable',

            // You may want to store the index outside the Model table
            // In that case let the engine know by setting this parameter to true.
//            'external' => true,

            // If you don't want scout to maintain the index for you
            // You can turn it off either for a Model or globally
//            'maintain_index' => true,

            // Ranking groups that will be assigned to fields
            // when document is being parsed.
            // Available groups: A, B, C and D.
            'rank' => [
                'fields' => [
                    'title' => 'A',
                    'sections' => 'B',
                ],
                // Ranking weights for searches.
                // [D-weight, C-weight, B-weight, A-weight].
                // Default [0.1, 0.2, 0.4, 1.0].
//                'weights' => [0.1, 0.2, 0.4, 1.0],
                // Ranking function [ts_rank | ts_rank_cd]. Default ts_rank.
//                'function' => 'ts_rank',

                // Normalization index. Default 0.
//                'normalization' => 32,
            ],
            // You can explicitly specify a PostgreSQL text search configuration for the model.
            // Use \dF in psql to see all available configurations in your database.
//            'config' => 'simple',
        ];
    }

    /**
     * Typesense schema to be created
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
     * Typesense The fields to be queried against
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
