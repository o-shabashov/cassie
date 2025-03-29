<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'id'       => 1,
            'name'     => 'Cassie Test User',
            'settings' => [
                'typesense'   => [
                    'host'                => 'typesense',
                    'path'                => '',
                    'port'                => '8108',
                    'api_key'             => 'key',
                    'protocol'            => 'http',
                    'products_index_name' => 'products_1',
                    'search_only_api_key' => 'key',
                ],
                'meilisearch' => [
                    'host'                => 'http://meilisearch:7700',
                    'api_key'             => '',
                    'products_index_name' => 'products_1',
                    'search_only_api_key' => 'key',
                ],
            ],
        ]);
    }
}
