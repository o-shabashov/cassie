<?php

declare(strict_types=1);

namespace App\Lib;

use App\Exceptions\ShopifyProductException;
use JsonException;
use Shopify\Auth\Session;
use Shopify\Clients\Graphql;
use Shopify\Clients\HttpResponse;
use Shopify\Exception\HttpRequestException;
use Shopify\Exception\MissingArgumentException;

class ProductIndex
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

    /**
     * @throws HttpRequestException
     * @throws ShopifyProductException
     * @throws MissingArgumentException
     * @throws JsonException
     */
    public static function call(Session $session): array|string|null
    {
        $client   = new Graphql($session->getShop(), $session->getAccessToken());
        $response = $client->query(
            [
                'query' => self::INDEX_PRODUCTS,
            ],
        );

        $body = HttpResponse::fromResponse($response)->getDecodedBody();

        if ($response->getStatusCode() !== 200 || isset($body['errors'])) {
            throw new ShopifyProductException($response->getBody()->__toString(), $response);
        }

        return $body;
    }
}
