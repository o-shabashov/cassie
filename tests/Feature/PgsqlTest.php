<?php

namespace Tests\Feature;

use App\Models\Pgsql\PgsqlPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\PgsqlTestCase;

class PgsqlTest extends PgsqlTestCase
{
    use RefreshDatabase;

    public function testCustomStructureIsSearchable(): void
    {
        $this->assertEquals('yoda', PgsqlPage::search('tease')->get()->first()->title);
        $this->assertEquals('anakin', PgsqlPage::search('rabbi')->get()->first()->title);
        $this->assertEquals('dart', PgsqlPage::search('red')->get()->first()->title);
        $this->assertEquals('dart', PgsqlPage::search('bla')->get()->first()->title);
        $this->assertEquals('dart', PgsqlPage::search('kill')->get()->first()->title);
    }

    public function testKeyNameIsNotSearchable(): void
    {
        $this->assertNull(PgsqlPage::search('suit')->get()->first());
    }

    public function testSubstringIsSearchable(): void
    {
        $searchResult = PgsqlPage::search('abbi')->get();

        $this->assertNotNull($searchResult);
        $this->assertEquals('anakin', $searchResult->first()->title);
    }

    public function testTitleHasPriorityOverSections(): void
    {
        $this->assertEquals('anakin young', PgsqlPage::search('anakin you')->get()->first()->title);
        $this->assertEquals('anakin', PgsqlPage::search('anakin')->get()->first()->title);
        $this->assertEquals('yoda', PgsqlPage::search('yoda')->get()->first()->title);
    }
}
