<?php

namespace Tests;

use App\Models\Typesense;

abstract class TypesensePageSearchTestCase extends PageSearchTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Typesense\Page::all()->searchable();
        sleep(1); // Waiting for the index to be ready
    }

    protected function tearDown(): void
    {
        $this->artisan('scout:flush', ['model' => Typesense\Page::class]);

        parent::tearDown();
    }
}
