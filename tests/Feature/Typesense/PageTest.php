<?php

namespace Tests\Feature\Typesense;

use App\Models\Typesense\TypesensePage;
use Tests\TypesensePageSearchTestCase;

class PageTest extends TypesensePageSearchTestCase
{
    public function testCustomStructureIsSearchable(): void
    {
        $this->assertEquals('yoda', TypesensePage::search('tease')->get()->first()->title);
        $this->assertEquals('anakin', TypesensePage::search('rabbi')->get()->first()->title);
        $this->assertEquals('dart', TypesensePage::search('red')->get()->first()->title);
        $this->assertEquals('dart', TypesensePage::search('bla')->get()->first()->title);
        $this->assertEquals('dart', TypesensePage::search('kill')->get()->first()->title);
    }

    public function testKeyNameIsNotSearchable(): void
    {
        $this->assertNull(TypesensePage::search('suit')->get()->first());
    }

    public function testSubstringIsSearchable(): void
    {
        $searchResult = TypesensePage::search('rabb')->get();

        $this->assertNotNull($searchResult);
        $this->assertEquals('anakin', $searchResult->first()->title);
    }

    public function testSubstringInTitleIsSearchable(): void
    {
        $searchResult = TypesensePage::search('dar')->get();

        $this->assertNotNull($searchResult);
        $this->assertEquals('dart', $searchResult->first()->title);
    }

    public function testTitleHasPriorityOverSections(): void
    {
        $this->assertEquals('anakin young', TypesensePage::search('anakin you')->get()->first()->title);
        $this->assertEquals('anakin', TypesensePage::search('anakin')->get()->first()->title);
        $this->assertEquals('yoda', TypesensePage::search('yoda')->get()->first()->title);
    }
}
