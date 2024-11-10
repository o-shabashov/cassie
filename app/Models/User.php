<?php

namespace App\Models;

use App\Enums\SearchEngines;
use App\Enums\UserPlatforms;
use App\Models\Meilisearch\MeilisearchProduct;
use App\Models\Typesense\TypesenseProduct;
use Illuminate\Database\Eloquent\Casts\ArrayObject;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $name
 * @property string $remember_token
 * @property string|Carbon $created_at
 * @property string|Carbon $updated_at
 * @property string $shopify_access_token
 * @property UserPlatforms $platform
 * @property SearchEngines $current_engine
 * @property ArrayObject $settings
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'shopify_access_token',
        'platform',
        'current_engine',
        'settings',
    ];

    /**
     * @var array<int, string>
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'platform'       => UserPlatforms::class,
            'current_engine' => SearchEngines::class,
            'settings'       => AsArrayObject::class,
        ];
    }

    public static function generateSettings(): array
    {
        return [
            'meilisearch' => [
                'host'                => config('scout.meilisearch.host'),
                'products_index_name' => (new MeilisearchProduct)->searchableAs(),
                'api_key'             => config('scout.meilisearch.key'), // FIXME should be user specific
                'search_only_api_key' => 'key', // FIXME
            ],
            'typesense' => array_merge(
                Arr::random(config('scout.typesense.nodes')),
                [
                    'products_index_name' => (new TypesenseProduct)->searchableAs(),
                    'api_key'             => 'key', // FIXME should be user specific
                    'search_only_api_key' => 'key', // FIXME
                ],
            ),
        ];
    }
}
