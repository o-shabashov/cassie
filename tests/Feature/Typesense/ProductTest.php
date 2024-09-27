<?php

namespace Tests\Feature\Typesense;

use App\Models\Typesense;
use Tests\TypesensePageSearchTestCase;

class ProductTest extends TypesensePageSearchTestCase
{
    public function testCustomStructureIsSearchable(): void
    {
        $this->assertEquals('yoda', Typesense\Product::search('tease')->get()->first()->title);
        $this->assertEquals('anakin', Typesense\Product::search('rabbi')->get()->first()->title);
        $this->assertEquals('dart', Typesense\Product::search('red')->get()->first()->title);
        $this->assertEquals('dart', Typesense\Product::search('bla')->get()->first()->title);
        $this->assertEquals('dart', Typesense\Product::search('kill')->get()->first()->title);
    }

    public function testKeyNameIsNotSearchable(): void
    {
        $this->assertNull(Typesense\Product::search('suit')->get()->first());
    }

    public function testSubstringIsSearchable(): void
    {
        $searchResult = Typesense\Product::search('rabb')->get();

        $this->assertNotNull($searchResult);
        $this->assertEquals('anakin', $searchResult->first()->title);
    }

    public function testSubstringInTitleIsSearchable(): void
    {
        $searchResult = Typesense\Product::search('dar')->get();

        $this->assertNotNull($searchResult);
        $this->assertEquals('dart', $searchResult->first()->title);
    }

    public function testTitleHasPriorityOverSections(): void
    {
        $this->assertEquals('anakin young', Typesense\Product::search('anakin you')->get()->first()->title);
        $this->assertEquals('anakin', Typesense\Product::search('anakin')->get()->first()->title);
        $this->assertEquals('yoda', Typesense\Product::search('yoda')->get()->first()->title);
    }
}
