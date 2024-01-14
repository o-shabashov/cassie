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
            'sections' => ['find me', 'should you'],
        ]);

        Page::factory()->create([
            'title' => 'dart',
            'sections' => ['moll' => ['black', 'suit' => 'red'], 'gonna kill you'],
        ]);

        $this->assertEquals('yoda', Page::search('fake')->get()->first()->title);
        $this->assertEquals('anakin', Page::search('should you')->get()->first()->title);
        $this->assertEquals('dart', Page::search('suit')->get()->first()->title);}
}
