<?php

namespace Tests\Feature;

use App\Models\Meilisearch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\MeilisearchFeatureTestCase;

class MeilisearchTest extends MeilisearchFeatureTestCase
{
    use RefreshDatabase;

    public function testCustomStructureIsSearchable(): void
    {
        $this->assertEquals('yoda', Meilisearch\Page::search('tease')->get()->first()->title);
        $this->assertEquals('anakin', Meilisearch\Page::search('rabbi')->get()->first()->title);
        $this->assertEquals('dart', Meilisearch\Page::search('red')->get()->first()->title);
        $this->assertEquals('dart', Meilisearch\Page::search('bla')->get()->first()->title);
        $this->assertEquals('dart', Meilisearch\Page::search('kill')->get()->first()->title);
    }

    public function testKeyNameIsNotSearchable(): void
    {
        $this->assertNull(Meilisearch\Page::search('suit')->get()->first());
    }

    public function testSubstringIsSearchable(): void
    {
        $searchResult = Meilisearch\Page::search('rabb')->get();

        $this->assertNotNull($searchResult);
        $this->assertEquals('anakin', $searchResult->first()->title);
    }

    public function testSubstringInTitleIsSearchable(): void
    {
        $searchResult = Meilisearch\Page::search('dar')->get();

        $this->assertNotNull($searchResult);
        $this->assertEquals('dart', $searchResult->first()->title);
    }

    public function testTitleHasPriorityOverSections(): void
    {
        $this->assertEquals('anakin young', Meilisearch\Page::search('anakin you')->get()->first()->title);
        $this->assertEquals('anakin', Meilisearch\Page::search('anakin')->get()->first()->title);
        $this->assertEquals('yoda', Meilisearch\Page::search('yoda')->get()->first()->title);
    }
}
