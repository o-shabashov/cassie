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

        $result = ShopifyProduct::get($api);

        $this->assertEquals(200, $result['status']);
        $this->assertFalse($result['errors']);
        $this->assertFalse(data_get($result, 'body.container.data.products.pageInfo.hasNextPage'));
        $this->assertNotEmpty(data_get($result, 'body.container.data.products.nodes'));

        $sampleProduct = '{
          "id": "gid://shopify/Product/9540562813216",
          "tags": [],
          "title": "Green Snowboard",
          "options": [
            {
              "name": "Size",
              "id": "gid://shopify/ProductOption/12051755991328",
              "position": 1,
              "values": [
                "Medium"
              ]
            },
            {
              "name": "Color",
              "id": "gid://shopify/ProductOption/12051756024096",
              "position": 2,
              "values": [
                "Black",
                "White"
              ]
            }
          ],
          "metafields": {
            "nodes": [
              {
                "id": "gid://shopify/Metafield/40984353833248",
                "key": "snowboard_length",
                "value": "{\"value\":42.0,\"unit\":\"MILLIMETERS\"}",
                "jsonValue": {
                  "value": 42,
                  "unit": "MILLIMETERS"
                }
              },
              {
                "id": "gid://shopify/Metafield/40984353866016",
                "key": "binding_mount",
                "value": "505 text",
                "jsonValue": "505 text"
              },
              {
                "id": "gid://shopify/Metafield/40984370970912",
                "key": "title_tag",
                "value": "Green Snowboard (Search engine listing)",
                "jsonValue": "Green Snowboard (Search engine listing)"
              },
              {
                "id": "gid://shopify/Metafield/40984371003680",
                "key": "description_tag",
                "value": "Meta description (Search engine listing)",
                "jsonValue": "Meta description (Search engine listing)"
              }
            ]
          },
          "handle": "green-snowboard-search-engine-listing",
          "vendor": "Quickstart (dbca5a72)",
          "status": "ACTIVE",
          "updatedAt": "2024-08-24T10:21:39Z",
          "createdAt": "2024-07-16T15:55:49Z",
          "priceRangeV2": {
            "minVariantPrice": {
              "amount": "35.93"
            }
          },
          "featuredImage": null,
          "description": "Description",
          "variants": {
            "pageInfo": {
              "endCursor": "eyJsYXN0X2lkIjo0OTEwNjY2ODYxODAxNiwibGFzdF92YWx1ZSI6IjMifQ==",
              "startCursor": "eyJsYXN0X2lkIjo0OTEwNjY2ODU4NTI0OCwibGFzdF92YWx1ZSI6IjIifQ=="
            },
            "nodes": [
              {
                "id": "gid://shopify/ProductVariant/49106668585248",
                "title": "Medium / Black",
                "displayName": "Green Snowboard - Medium / Black",
                "price": "35.93"
              },
              {
                "id": "gid://shopify/ProductVariant/49106668618016",
                "title": "Medium / White",
                "displayName": "Green Snowboard - Medium / White",
                "price": "35.93"
              }
            ]
          }
        }';
    }
}
