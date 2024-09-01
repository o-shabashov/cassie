<?php

declare(strict_types=1);

namespace App\Lib;

use App\Exceptions\ShopifyProductException;
use Shopify\Auth\Session;
use Shopify\Clients\Graphql;
use Shopify\Clients\HttpResponse;

class ProductCreator
{
    private const CREATE_PRODUCTS_MUTATION = <<<'QUERY'
    mutation populateProduct($input: ProductInput!) {
        productCreate(input: $input) {
            product {
                id
            }
        }
    }
    QUERY;

    public static function call(Session $session, int $count)
    {
        $client = new Graphql($session->getShop(), $session->getAccessToken());

        for ($i = 0; $i < $count; $i++) {
            $response = $client->query(
                [
                    'query'     => self::CREATE_PRODUCTS_MUTATION,
                    'variables' => [
                        'input' => [
                            'title' => fake()->words(2, true),
                        ],
                    ],
                ],
            );

            $body = HttpResponse::fromResponse($response)->getDecodedBody();

            if ($response->getStatusCode() !== 200 || isset($body['errors'])) {
                throw new ShopifyProductException($response->getBody()->__toString(), $response);
            }
        }
    }
}
