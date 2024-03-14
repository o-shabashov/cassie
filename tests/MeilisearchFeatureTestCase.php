<?php

namespace Tests;

use App\Models\Meilisearch;

abstract class MeilisearchFeatureTestCase extends FeatureTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Meilisearch\Page::all()->searchable();
        sleep(1);
    }

    protected function tearDown(): void
    {
        $this->artisan('scout:flush', ['model' => Meilisearch\Page::class]);

        parent::tearDown();
    }
}
