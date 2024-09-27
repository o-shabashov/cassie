<?php

namespace Feature\Factory;

use Database\Factories\ShopifyProductResponseFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase;
use Tests\CreatesApplication;

class ShopifyProductFactoryTest extends TestCase
{

    use CreatesApplication;
    use DatabaseTransactions;

    public function testFactoryDataCouldBeIndexedBySearchEngines()
    {
        $shopifyProducts = ShopifyProductResponseFactory::count(10)->make();
    }
}
