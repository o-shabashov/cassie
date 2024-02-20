<?php

namespace Tests;

use App\Models\Page;
use App\Models\Meilisearch;

abstract class MeilisearchTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Page::factory()->createMany([
            [
                'title'    => 'yoda',
                'sections' => ['property' => 'fake me', 'value' => 'tease me'],
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
                'sections' => ['property' => 'anakin', 'value' => 'tease me', 'anakin'],
            ],
            [
                'title'    => 'anakin young',
                'sections' => ['yoda', 'still yoda'],
            ]
        ]);

        Meilisearch\Page::all()->searchable();
        sleep(1);
    }

    protected function tearDown(): void
    {
        $this->artisan('scout:flush', ['model' => Meilisearch\Page::class]);

        parent::tearDown();
    }
}
