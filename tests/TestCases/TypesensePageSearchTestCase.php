<?php

namespace Tests\TestCases;

use App\Models\Typesense\TypesensePage;

abstract class TypesensePageSearchTestCase extends PageSearchTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        TypesensePage::all()->searchable();
        sleep(1); // Waiting for the index to be ready
    }

    protected function tearDown(): void
    {
        $this->artisan('scout:flush', ['model' => TypesensePage::class]);

        parent::tearDown();
    }
}
