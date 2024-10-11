<?php

namespace Tests\TestCases;

use App\Models\Meilisearch;

abstract class MeilisearchPageSearchTestCase extends PageSearchTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Meilisearch\MeilisearchPage::all()->searchable();
        sleep(1); // Waiting for the index to be ready
    }

    protected function tearDown(): void
    {
        $this->artisan('scout:flush', ['model' => Meilisearch\MeilisearchPage::class]);

        parent::tearDown();
    }
}
