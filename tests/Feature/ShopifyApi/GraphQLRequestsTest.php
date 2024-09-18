<?php

namespace Tests\Feature\ShopifyApi;

use App\Models\User;
use Gnikyt\BasicShopifyAPI\BasicShopifyAPI;
use JetBrains\PhpStorm\NoReturn;
use Tests\FeatureTestCase;

class GraphQLRequestsTest extends FeatureTestCase
{
    private const INDEX_PRODUCTS = <<<'GRAPHQL'
     query {
      products(first: 250) {
        pageInfo {
          endCursor
          hasNextPage
        }
        nodes {
          id
          tags
          title
          options {
            id
            name
            position
            values
          }
          metafields(first: 250) {
            nodes {
              id
              key
              value
              jsonValue
            }
          }
          handle
          vendor
          status
          updatedAt
          createdAt
          priceRangeV2 {
            minVariantPrice {
              amount
            }
          }
          featuredImage {
            id
            url
          }
          description
          variants(first: 100) {
            pageInfo {
              endCursor
              startCursor
            }
            nodes {
              id
              title
              displayName
              price
            }
          }
        }
      }
    }
GRAPHQL;

    #[NoReturn]
    public function testBasic()
    {
        $user = User::factory()->create([
            'shopify_access_token' => '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',
            'name'                 => 'quickstart-dbca5a72.myshopify.com',
        ]);

        $this->actingAs($user);

        $api = app(BasicShopifyAPI::class);

        $result = $api->graph(self::INDEX_PRODUCTS);
        dd($result);
    }
}
