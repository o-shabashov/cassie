<?php

namespace Tests;

use App\Models\Meilisearch;

abstract class MeilisearchPageSearchTestCase extends PageSearchTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Meilisearch\Page::all()->searchable();
        sleep(1); // Waiting for the index to be ready
    }

    protected function tearDown(): void
    {
        $this->artisan('scout:flush', ['model' => Meilisearch\Page::class]);

        parent::tearDown();
    }
}
