<?php

namespace Tests\Feature\Model;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    public function testUserSettingsCastedCorrectly(): void
    {
        $user = new User;

        // Fix ErrorException: Indirect modification of overloaded property App\Models\User::$settings has no effect
        $user->settings = [];

        $user->settings['meilisearch'] = ['host' => 'local'];
        data_set($user, 'settings.typesense.search_only_api_key', 'key');

        $this->assertEquals(
            [
                'typesense'   => ['search_only_api_key' => 'key'],
                'meilisearch' => ['host' => 'local'],
            ],
            $user->settings->toArray()
        );
        $this->assertEquals(['search_only_api_key' => 'key'], $user->settings['typesense']);
        $this->assertEquals(['host' => 'local'], $user->settings['meilisearch']);
    }

    public function testSettingsDefaultValue()
    {
        $user = User::factory()->create();

        $this->assertEquals([], $user->settings->toArray());

        $user->settings['meilisearch'] = ['host' => 'local'];
        data_set($user, 'settings.typesense.search_only_api_key', 'key');

        $this->assertEquals(
            [
                'typesense'   => ['search_only_api_key' => 'key'],
                'meilisearch' => ['host' => 'local'],
            ],
            $user->settings->toArray()
        );
        $this->assertEquals(['search_only_api_key' => 'key'], $user->settings['typesense']);
        $this->assertEquals(['host' => 'local'], $user->settings['meilisearch']);
    }

    public function testGenerateSettings()
    {
        $user = User::updateOrCreate(
            ['name' => 'test'],
            [
                'shopify_access_token' => 'token',
            ]
        );

        $this->actingAs($user);

        $user->settings = User::generateSettings($user);

        $this->assertEquals(
            [
                'search_only_api_key' => 'key',
                'host'                => 'typesense',
                'port'                => '8108',
                'path'                => '',
                'protocol'            => 'http',
                'products_index_name' => 'products_'.$user->id,
                'api_key'             => 'key',
            ],
            $user->settings['typesense']
        );
        $this->assertEquals(
            [
                'host'                => 'http://meilisearch:7700',
                'products_index_name' => 'products_'.$user->id,
                'api_key'             => null,
                'search_only_api_key' => 'key',
            ],
            $user->settings['meilisearch']
        );
    }
}
