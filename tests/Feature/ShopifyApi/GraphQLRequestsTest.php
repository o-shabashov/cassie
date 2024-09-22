<?php

namespace Tests\Feature\ShopifyApi;

use App\GraphQL\ShopifyProduct;
use App\Models\User;
use Gnikyt\BasicShopifyAPI\BasicShopifyAPI;
use JetBrains\PhpStorm\NoReturn;
use Tests\FeatureTestCase;

class GraphQLRequestsTest extends FeatureTestCase
{
    #[NoReturn]
    public function testBasic()
    {
        // TODO create real dedicated test app and use it access token / shop domain
        $user = User::factory()->create([
            'shopify_access_token' => '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',
            'name'                 => 'quickstart-dbca5a72.myshopify.com',
        ]);

        $this->actingAs($user);

        $api = app(BasicShopifyAPI::class);

        $result = ShopifyProduct::index($api);

        $this->assertEquals(200, $result['status']);
        $this->assertFalse($result['errors']);
        $this->assertFalse(data_get($result, 'body.container.data.products.pageInfo.hasNextPage'));
        $this->assertNotEmpty(data_get($result, 'body.container.data.products.nodes'));
    }
}
