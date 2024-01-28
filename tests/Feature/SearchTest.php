<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    public function testCustomStructureIsSearchable(): void
    {
        Page::factory()->create([
            'title' => 'yoda',
            'sections' => ['property' => 'fake me', 'value' => 'tease me'],
        ]);

        Page::factory()->create([
            'title' => 'anakin',
            'sections' => ['find me rabbit', 'should you tiger'],
        ]);

        Page::factory()->create([
            'title' => 'dart',
            'sections' => ['moll' => ['black', 'suit' => 'red'], 'gonna kill you'],
        ]);

//        $this->assertEquals('yoda', Page::search('fake')->get()->first()->title);
        $this->assertEquals('anakin', Page::search('rabb')->get()->first()->title);
//        $this->assertEquals('dart', Page::search('suit')->get()->first()->title);
//        $this->assertEquals('dart', Page::search('red')->get()->first()->title);
//        $this->assertEquals('dart', Page::search('il')->get()->first()->title);
    }

//    public function testTitleHasPriorityOverSections(): void
//    {
//        Page::factory()->create([
//            'title' => 'yoda master',
//            'sections' => ['property' => 'anakin', 'value' => 'tease me', 'anakin'],
//        ]);
//
//        Page::factory()->create([
//            'title' => 'anakin young',
//            'sections' => ['yoda', 'still yoda'],
//        ]);
//
//        $this->assertEquals('anakin', Page::search('anakin you')->get()->first()->title);
//        $this->assertEquals('yoda', Page::search('yoda')->get()->first()->title);
//    }
}
