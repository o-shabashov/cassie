<?php

namespace Tests\Feature\Typesense;

use App\Models\Product;
use App\Models\Typesense\TypesenseProduct;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase;

class ProductTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());

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

        TypesenseProduct::all()->searchable();
        sleep(1); // Waiting for the index to be ready
    }

    protected function tearDown(): void
    {
        $this->artisan('scout:flush', ['model' => TypesenseProduct::class]);

        parent::tearDown();
    }

    public function testSubstringInTitleIsSearchable(): void
    {
        $searchResult = TypesenseProduct::search('ana')->get();

        $this->assertNotNull($searchResult);
        $this->assertEquals('anakin young', $searchResult->first()->title);
    }

    public function testSubstringInDescriptionIsSearchable(): void
    {
        $searchResult = TypesenseProduct::search('blac')->get();

        $this->assertNotNull($searchResult);
        $this->assertEquals('dart', $searchResult->first()->title);
    }

    public function testTitleHasPriorityOverFields(): void
    {
        $this->assertEquals('me', TypesenseProduct::search('me')->get()->first()->title);
        $this->assertEquals('yoda', TypesenseProduct::search('yoda')->get()->first()->title);
    }
}
