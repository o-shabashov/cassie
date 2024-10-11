<?php

namespace Tests\Feature\Meilisearch;

use App\Models\Meilisearch\MeilisearchPage;
use Tests\TestCases\MeilisearchPageSearchTestCase;

class PageTest extends MeilisearchPageSearchTestCase
{
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
        $searchResult = MeilisearchPage::search('rabb')->get();

        $this->assertNotNull($searchResult);
        $this->assertEquals('anakin', $searchResult->first()->title);
    }

    public function testSubstringInTitleIsSearchable(): void
    {
        $searchResult = MeilisearchPage::search('dar')->get();

        $this->assertNotNull($searchResult);
        $this->assertEquals('dart', $searchResult->first()->title);
    }

    public function testTitleHasPriorityOverSections(): void
    {
        $this->assertEquals('anakin young', MeilisearchPage::search('anakin you')->get()->first()->title);
        $this->assertEquals('anakin', MeilisearchPage::search('anakin')->get()->first()->title);
        $this->assertEquals('yoda', MeilisearchPage::search('yoda')->get()->first()->title);
    }
}
