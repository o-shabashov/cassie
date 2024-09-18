<?php

namespace Tests\Feature\ShopifyApi;

use Gnikyt\BasicShopifyAPI\BasicShopifyAPI;
use Gnikyt\BasicShopifyAPI\Options;
use Gnikyt\BasicShopifyAPI\Session;
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
        $accessToken = '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';
        $shop        = 'quickstart-dbca5a72.myshopify.com';

        // Create options for the API
        $options = new Options();
        $options->setVersion(config('services.shopify.api_version'));

        // Create the client and session
        $api = new BasicShopifyAPI($options);
        $api->setSession(new Session($shop, $accessToken));

        // Now run your requests...
        $result = $api->graph(self::INDEX_PRODUCTS);
        dd($result);
    }
}
