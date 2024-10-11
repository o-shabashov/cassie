<?php

namespace Tests\Feature\Pgsql;

use App\Models\Page;
use App\Models\Pgsql\PgsqlPage;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase;

class PageTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        Page::factory()->createMany([
            [
                'title'    => 'yoda',
                'sections' => ['property' => 'fake me', 'value' => 'tease'],
            ],
            [
                'title'    => 'anakin',
                'sections' => ['find me rabbit', 'should you tiger'],
            ],
            [
                'title'    => 'dart',
                'sections' => ['moll' => ['black', 'suit' => 'red'], 'gonna kill you'],
            ],
            [
                'title'    => 'yoda master',
                'sections' => ['property' => 'anakin', 'value' => 'touch me', 'anakin'],
            ],
            [
                'title'    => 'anakin young',
                'sections' => ['yoda', 'still yoda'],
            ],
        ]);
    }

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
