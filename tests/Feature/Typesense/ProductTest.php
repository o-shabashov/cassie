<?php

namespace Tests\Feature\Typesense;

use App\Models\Typesense\TypesenseProduct;
use Tests\TestCases\TypesenseProductSearchTestCase;

class ProductTest extends TypesenseProductSearchTestCase
{
    public function testCustomStructureIsSearchable(): void
    {
        $this->assertEquals('yoda', TypesenseProduct::search('tease')->get()->first()->title);
        $this->assertEquals('anakin', TypesenseProduct::search('rabbi')->get()->first()->title);
        $this->assertEquals('dart', TypesenseProduct::search('red')->get()->first()->title);
        $this->assertEquals('dart', TypesenseProduct::search('bla')->get()->first()->title);
        $this->assertEquals('dart', TypesenseProduct::search('kill')->get()->first()->title);
    }

    public function testKeyNameIsNotSearchable(): void
    {
        $this->assertNull(TypesenseProduct::search('suit')->get()->first());
    }

    public function testSubstringIsSearchable(): void
    {
        $searchResult = TypesenseProduct::search('rabb')->get();

        $this->assertNotNull($searchResult);
        $this->assertEquals('anakin', $searchResult->first()->title);
    }

    public function testSubstringInTitleIsSearchable(): void
    {
        $searchResult = TypesenseProduct::search('dar')->get();

        $this->assertNotNull($searchResult);
        $this->assertEquals('dart', $searchResult->first()->title);
    }

    public function testTitleHasPriorityOverSections(): void
    {
        $this->assertEquals('anakin young', TypesenseProduct::search('anakin you')->get()->first()->title);
        $this->assertEquals('anakin', TypesenseProduct::search('anakin')->get()->first()->title);
        $this->assertEquals('yoda', TypesenseProduct::search('yoda')->get()->first()->title);
    }
}
