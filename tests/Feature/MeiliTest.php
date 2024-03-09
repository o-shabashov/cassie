<?php

namespace Tests\Feature;

use App\Models\Meilisearch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\MeilisearchTestCase;

class MeiliTest extends MeilisearchTestCase
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
        $this->markTestSkipped('Meilisearch does not support substring search. Waiting till pgsql will be ready');
        $searchResult = Meilisearch\Page::search('abbi')->get();

        $this->assertNotNull($searchResult);
        $this->assertEquals('anakin', $searchResult->first()->title);
    }

    public function testTitleHasPriorityOverSections(): void
    {
        $this->assertEquals('anakin young', Meilisearch\Page::search('anakin you')->get()->first()->title);
        $this->assertEquals('anakin', Meilisearch\Page::search('anakin')->get()->first()->title);
        $this->assertEquals('yoda', Meilisearch\Page::search('yoda')->get()->first()->title);
    }
}
