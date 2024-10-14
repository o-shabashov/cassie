<?php

namespace Tests\Feature\Meilisearch;

use App\Models\Meilisearch\MeilisearchProduct;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase;
use Meilisearch\Client;

class ProductTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $client = new Client(config('scout.meilisearch.host'));
        $client->index('products')->updateSettings(
            config('scout.meilisearch.index-settings.'.MeilisearchProduct::class)
        );

        Product::factory()->createMany([
            [
                'title'  => 'yoda',
                'fields' => ['title' => 'fake me', 'description' => 'anakin force should you'],
            ],
            [
                'title'  => 'dart',
                'fields' => ['description' => 'blacked suit red, gonna kill you'],
            ],
            [
                'title'  => 'anakin young',
                'fields' => ['description' => 'yoda still yoda'],
            ],
            [
                'title' => 'me',
            ],
        ]);


        MeilisearchProduct::all()->searchable();
        sleep(1); // Waiting for the index to be ready
    }

    protected function tearDown(): void
    {
        $this->artisan('scout:flush', ['model' => MeilisearchProduct::class]);

        parent::tearDown();
    }

    public function testSubstringInTitleIsSearchable(): void
    {
        $searchResult = MeilisearchProduct::search('ana')->orderBy('id', 'DESC')->get();

        $this->assertNotNull($searchResult);
        $this->assertEquals('anakin young', $searchResult->first()->title);
    }

    public function testSubstringInDescriptionIsSearchable(): void
    {
        $searchResult = MeilisearchProduct::search('blac')->get();

        $this->assertNotNull($searchResult);
        $this->assertEquals('dart', $searchResult->first()->title);
    }

    public function testTitleHasPriorityOverFields(): void
    {
        $this->assertEquals('me', MeilisearchProduct::search('me')->get()->first()->title);
        $this->assertEquals('yoda', MeilisearchProduct::search('yoda')->get()->first()->title);
    }
}
