<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\PgsqlTestCase;
use App\Models\Pgsql;

class PgsqlTest extends PgsqlTestCase
{
    use RefreshDatabase;

    public function testCustomStructureIsSearchable(): void
    {
        $this->assertEquals('yoda', Pgsql\Page::search('tease')->usingPlainQuery()->get()->first()->title);
        $this->assertEquals('anakin', Pgsql\Page::search('rabbi')->get()->first()->title);
        $this->assertEquals('dart', Pgsql\Page::search('red')->get()->first()->title);
        $this->assertEquals('dart', Pgsql\Page::search('bla')->get()->first()->title);
        $this->assertEquals('dart', Pgsql\Page::search('kill')->get()->first()->title);
    }

    public function testKeyNameIsNotSearchable(): void
    {
        $this->assertNull(Pgsql\Page::search('suit')->get()->first());
    }

    public function testSubstringIsSearchable(): void
    {
        $searchResult = Pgsql\Page::search('abbi')->get();

        $this->assertNotNull($searchResult);
        $this->assertEquals('anakin', $searchResult->first()->title);
    }

    public function testTitleHasPriorityOverSections(): void
    {
        $this->assertEquals('anakin young', Pgsql\Page::search('anakin you')->get()->first()->title);
        $this->assertEquals('anakin', Pgsql\Page::search('anakin')->get()->first()->title);
        $this->assertEquals('yoda', Pgsql\Page::search('yoda')->get()->first()->title);
    }
}
