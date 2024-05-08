<?php

namespace Tests\Feature\Pgsql;

use App\Models\Pgsql;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\FeatureTestCase;

class PageTest extends FeatureTestCase
{
    use RefreshDatabase;

    public function testCustomStructureIsSearchable(): void
    {
        $this->assertEquals('yoda', Pgsql\Page::search('tease')->get()->first()->title);
        $this->assertEquals('anakin', Pgsql\Page::search('rabbi')->get()->first()->title);
        $this->assertEquals('dart', Pgsql\Page::search('red')->get()->first()->title);
        $this->assertEquals('dart', Pgsql\Page::search('bla')->get()->first()->title);
        $this->assertEquals('dart', Pgsql\Page::search('kill')->get()->first()->title);
    }

    public function testKeyNameIsSearchable(): void
    {
        $this->assertEquals('dart', Pgsql\Page::search('suit')->get()->first()->title);
    }

    public function testSubstringInSectionsIsSearchable(): void
    {
        $searchResult = Pgsql\Page::search('rabbi')->get();

        $this->assertNotNull($searchResult);
        $this->assertEquals('anakin', $searchResult->first()->title);
    }

    public function testSubstringInTitleIsSearchable(): void
    {
        $searchResult = Pgsql\Page::search('dar')->get();

        $this->assertNotNull($searchResult);
        $this->assertEquals('dart', $searchResult->first()->title);
    }

    public function testTitleHasPriorityOverSections(): void
    {
        $this->markTestSkipped('Ranking not implemented yet');

        $this->assertEquals('anakin young', Pgsql\Page::search('anakin you')->get()->first()->title);
        $this->assertEquals('anakin', Pgsql\Page::search('anakin')->get()->first()->title);
        $this->assertEquals('yoda', Pgsql\Page::search('yoda')->get()->first()->title);
    }
}
