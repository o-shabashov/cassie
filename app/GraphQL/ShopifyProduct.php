<?php

namespace App\GraphQL;

use Gnikyt\BasicShopifyAPI\BasicShopifyAPI;
use GuzzleHttp\Promise\Promise;

class ShopifyProduct
{
    public static function index(
        BasicShopifyAPI $api,
        int $productsCount = 250,
        int $metafieldsCount = 250,
        int $variantsCount = 100,
        bool $async = false,
    ): Promise|array {
        $query = "
        query {
          products(first: $productsCount) {
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
              metafields(first: $metafieldsCount) {
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
              variants(first: $variantsCount) {
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
        }";

        return match ($async) {
            true    => $api->graphAsync($query),
            default => $api->graph($query),
        };
    }

    public static function create(BasicShopifyAPI $api, int $productsCount = 2): Promise|array
    {
        $query = '
        mutation populateProduct($input: ProductInput!) {
            productCreate(input: $input) {
                product {
                    id
                }
            }
        }';

        $response = [];

        for ($i = 0; $i < $productsCount; $i++) {
            $response = $api->graph($query, [
                'input' => [
                    'title' => fake()->words(2, true),
                ],
            ]);
        }

        return $response;
    }
}
