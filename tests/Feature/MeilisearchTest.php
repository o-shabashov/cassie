<?php

namespace Tests\Feature;

use App\Models\Meilisearch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\MeilisearchTestCase;

class MeilisearchTest extends MeilisearchTestCase
{
    use RefreshDatabase;

    public function testCustomStructureIsSearchable(): void
    {
        $this->assertEquals('yoda', Meilisearch\MeilisearchPage::search('tease')->get()->first()->title);
        $this->assertEquals('anakin', Meilisearch\MeilisearchPage::search('rabbi')->get()->first()->title);
        $this->assertEquals('dart', Meilisearch\MeilisearchPage::search('red')->get()->first()->title);
        $this->assertEquals('dart', Meilisearch\MeilisearchPage::search('bla')->get()->first()->title);
        $this->assertEquals('dart', Meilisearch\MeilisearchPage::search('kill')->get()->first()->title);
    }

    public function testKeyNameIsNotSearchable(): void
    {
        $this->assertNull(Meilisearch\MeilisearchPage::search('suit')->get()->first());
    }

    public function testSubstringIsSearchable(): void
    {
        $this->markTestSkipped('Meilisearch does not support substring search. Waiting till pgsql will be ready');
        $searchResult = Meilisearch\MeilisearchPage::search('abbi')->get();

        $this->assertNotNull($searchResult);
        $this->assertEquals('anakin', $searchResult->first()->title);
    }

    public function testTitleHasPriorityOverSections(): void
    {
        $this->assertEquals('anakin young', Meilisearch\MeilisearchPage::search('anakin you')->get()->first()->title);
        $this->assertEquals('anakin', Meilisearch\MeilisearchPage::search('anakin')->get()->first()->title);
        $this->assertEquals('yoda', Meilisearch\MeilisearchPage::search('yoda')->get()->first()->title);
    }
}
