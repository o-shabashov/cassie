<?php

namespace Tests\Feature\Pgsql;

use App\Models\Pgsql\PgsqlPage;
use Tests\TestCases\PageSearchTestCase;

class PageTest extends PageSearchTestCase
{
    public function testCustomStructureIsSearchable(): void
    {
        $this->assertEquals('yoda', PgsqlPage::search('tease')->get()->first()->title);
        $this->assertEquals('anakin', PgsqlPage::search('rabbi')->get()->first()->title);
        $this->assertEquals('dart', PgsqlPage::search('red')->get()->first()->title);
        $this->assertEquals('dart', PgsqlPage::search('bla')->get()->first()->title);
        $this->assertEquals('dart', PgsqlPage::search('kill')->get()->first()->title);
    }

    public function testKeyNameIsSearchable(): void
    {
        $this->assertEquals('dart', PgsqlPage::search('suit')->get()->first()->title);
    }

    public function testSubstringInSectionsIsSearchable(): void
    {
        $searchResult = PgsqlPage::search('rabbi')->get();

        $this->assertNotNull($searchResult);
        $this->assertEquals('anakin', $searchResult->first()->title);
    }

    public function testSubstringInTitleIsSearchable(): void
    {
        $searchResult = PgsqlPage::search('dar')->get();

        $this->assertNotNull($searchResult);
        $this->assertEquals('dart', $searchResult->first()->title);
    }

    public function testTitleHasPriorityOverSections(): void
    {
        $this->markTestSkipped('Ranking not implemented yet');

        $this->assertEquals('anakin young', PgsqlPage::search('anakin you')->get()->first()->title);
        $this->assertEquals('anakin', PgsqlPage::search('anakin')->get()->first()->title);
        $this->assertEquals('yoda', PgsqlPage::search('yoda')->get()->first()->title);
    }
}
