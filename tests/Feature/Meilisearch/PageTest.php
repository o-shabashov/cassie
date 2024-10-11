<?php

namespace Tests\Feature\Meilisearch;

use App\Models\Meilisearch\MeilisearchPage;
use App\Models\Page;
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
        MeilisearchPage::all()->searchable();
        sleep(1); // Waiting for the index to be ready
    }

    protected function tearDown(): void
    {
        $this->artisan('scout:flush', ['model' => MeilisearchPage::class]);

        parent::tearDown();
    }

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
