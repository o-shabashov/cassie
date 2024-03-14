<?php

namespace Tests\Feature;

use App\Models\Typesense;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TypesenseFeatureTestCase;

class TypesenseTest extends TypesenseFeatureTestCase
{
    use RefreshDatabase;

    public function testCustomStructureIsSearchable(): void
    {
        $this->assertEquals('yoda', Typesense\Page::search('tease')->get()->first()->title);
        $this->assertEquals('anakin', Typesense\Page::search('rabbi')->get()->first()->title);
        $this->assertEquals('dart', Typesense\Page::search('red')->get()->first()->title);
        $this->assertEquals('dart', Typesense\Page::search('bla')->get()->first()->title);
        $this->assertEquals('dart', Typesense\Page::search('kill')->get()->first()->title);
    }

    public function testKeyNameIsNotSearchable(): void
    {
        $this->assertNull(Typesense\Page::search('suit')->get()->first());
    }

    public function testSubstringIsSearchable(): void
    {
        $searchResult = Typesense\Page::search('rabb')->get();

        $this->assertNotNull($searchResult);
        $this->assertEquals('anakin', $searchResult->first()->title);
    }

    public function testSubstringInTitleIsSearchable(): void
    {
        $searchResult = Typesense\Page::search('dar')->get();

        $this->assertNotNull($searchResult);
        $this->assertEquals('dart', $searchResult->first()->title);
    }

    public function testTitleHasPriorityOverSections(): void
    {
        $this->assertEquals('anakin young', Typesense\Page::search('anakin you')->get()->first()->title);
        $this->assertEquals('anakin', Typesense\Page::search('anakin')->get()->first()->title);
        $this->assertEquals('yoda', Typesense\Page::search('yoda')->get()->first()->title);
    }
}
