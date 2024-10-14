<?php

use App\Models\Meilisearch\MeilisearchPage;
use App\Models\Meilisearch\MeilisearchProduct;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Search Engine
    |--------------------------------------------------------------------------
    |
    | This option controls the default search connection that gets used while
    | using Laravel Scout. This connection is used when syncing all models
    | to the search service. You should adjust this based on your needs.
    |
    | Supported: "algolia", "meilisearch", "database", "collection", "null"
    |
    */

    'driver' => env('SCOUT_DRIVER', 'algolia'),

    /*
    |--------------------------------------------------------------------------
    | Index Prefix
    |--------------------------------------------------------------------------
    |
    | Here you may specify a prefix that will be applied to all search index
    | names used by Scout. This prefix may be useful if you have multiple
    | "tenants" or applications sharing the same search infrastructure.
    |
    */

    'prefix' => env('SCOUT_PREFIX', ''),

    /*
    |--------------------------------------------------------------------------
    | Queue Data Syncing
    |--------------------------------------------------------------------------
    |
    | This option allows you to control if the operations that sync your data
    | with your search engines are queued. When this is set to "true" then
    | all automatic data syncing will get queued for better performance.
    |
    */

    'queue' => env('SCOUT_QUEUE', false),

    /*
    |--------------------------------------------------------------------------
    | Database Transactions
    |--------------------------------------------------------------------------
    |
    | This configuration option determines if your data will only be synced
    | with your search indexes after every open database transaction has
    | been committed, thus preventing any discarded data from syncing.
    |
    */

    'after_commit' => false,

    /*
    |--------------------------------------------------------------------------
    | Chunk Sizes
    |--------------------------------------------------------------------------
    |
    | These options allow you to control the maximum chunk size when you are
    | mass importing data into the search engine. This allows you to fine
    | tune each of these chunk sizes based on the power of the servers.
    |
    */

    'chunk' => [
        'searchable'   => 500,
        'unsearchable' => 500,
    ],

    /*
    |--------------------------------------------------------------------------
    | Soft Deletes
    |--------------------------------------------------------------------------
    |
    | This option allows to control whether to keep soft deleted records in
    | the search indexes. Maintaining soft deleted records can be useful
    | if your application still needs to search for the records later.
    |
    */

    'soft_delete' => false,

    /*
    |--------------------------------------------------------------------------
    | Identify User
    |--------------------------------------------------------------------------
    |
    | This option allows you to control whether to notify the search engine
    | of the user performing the search. This is sometimes useful if the
    | engine supports any analytics based on this application's users.
    |
    | Supported engines: "algolia"
    |
    */

    'identify' => env('SCOUT_IDENTIFY', false),

    'typesense' => [
        'api_key' => env('TYPESENSE_API_KEY', 'xyz'),
        'nodes'   => [
            [
                'host'     => 'typesense',
                'port'     => '8108',
                'path'     => '',
                'protocol' => 'http',
            ],
        ],
        'nearest_node' => [
            'host'     => 'typesense',
            'port'     => '8108',
            'path'     => '',
            'protocol' => 'http',
        ],
        'connection_timeout_seconds'   => 2,
        'healthcheck_interval_seconds' => 30,
        'num_retries'                  => 3,
        'retry_interval_seconds'       => 1,
    ],

    'meilisearch' => [
        'host' => env('MEILISEARCH_HOST', 'http://localhost:7700'),
        'key'  => env('MEILISEARCH_KEY'),

        /**
         * @see https://www.meilisearch.com/docs/reference/api/settings
         */
        'index-settings' => [
            MeilisearchPage::class => [
                'filterableAttributes' => ['id', 'title', 'url', 'sections'],
                'sortableAttributes'   => ['id', 'created_at'],
            ],
            MeilisearchProduct::class => [
                'filterableAttributes' => ['id', 'title', 'url', 'fields'],
                'sortableAttributes'   => ['id', 'created_at'],
            ],
        ],
    ],
];
