<?php

namespace Tests\Feature\Typesense;

use App\Models\Page;
use App\Models\Typesense\TypesensePage;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase;

class PageTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());

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

        TypesensePage::all()->searchable();
        sleep(1); // Waiting for the index to be ready
    }

    protected function tearDown(): void
    {
        $this->artisan('scout:flush', ['model' => TypesensePage::class]);

        parent::tearDown();
    }

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
