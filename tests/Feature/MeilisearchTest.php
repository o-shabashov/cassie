<?php

namespace Tests\Feature;

use App\Models\Meilisearch\MeilisearchPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\MeilisearchTestCase;

class MeilisearchTest extends MeilisearchTestCase
{
    use RefreshDatabase;

    public function testCustomStructureIsSearchable(): void
    {
        $this->assertEquals('yoda', MeilisearchPage::search('tease')->get()->first()->title);
        $this->assertEquals('anakin', MeilisearchPage::search('rabbi')->get()->first()->title);
        $this->assertEquals('dart', MeilisearchPage::search('red')->get()->first()->title);
        $this->assertEquals('dart', MeilisearchPage::search('bla')->get()->first()->title);
        $this->assertEquals('dart', MeilisearchPage::search('kill')->get()->first()->title);
    }

    public function testKeyNameIsNotSearchable(): void
    {
        $this->assertNull(MeilisearchPage::search('suit')->get()->first());
    }

    public function testSubstringIsSearchable(): void
    {
        $this->markTestSkipped('Meilisearch does not support substring search. Waiting till pgsql will be ready');
        $searchResult = MeilisearchPage::search('abbi')->get();

        $this->assertNotNull($searchResult);
        $this->assertEquals('anakin', $searchResult->first()->title);
    }

    public function testTitleHasPriorityOverSections(): void
    {
        $this->assertEquals('anakin young', MeilisearchPage::search('anakin you')->get()->first()->title);
        $this->assertEquals('anakin', MeilisearchPage::search('anakin')->get()->first()->title);
        $this->assertEquals('yoda', MeilisearchPage::search('yoda')->get()->first()->title);
    }
}
