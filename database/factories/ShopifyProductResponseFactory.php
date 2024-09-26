<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ShopifyProductResponseFactory extends Factory
{
    protected $model = null;

    public function definition(): array
    {
        return [
            'id'            => 'gid://shopify/Product/'.$this->faker->randomNumber(13),
            'tags'          => $this->faker->words(3, true),
            'title'         => $this->faker->sentence,
            'options'       => $this->generateOptions(),
            'metafields'    => $this->generateMetafields(),
            'handle'        => $this->faker->slug,
            'vendor'        => $this->faker->company,
            'status'        => $this->faker->randomElement(['ACTIVE', 'DRAFT', 'ARCHIVED']),
            'updatedAt'     => $this->faker->dateTimeBetween('-1 year', '-1 day'),
            'createdAt'     => $this->faker->dateTimeBetween('-1 year', '-1 day'),
            'priceRangeV2'  => ['minVariantPrice' => ['amount' => $this->faker->randomFloat(2, 1, 100)]],
            'featuredImage' => null,
            'description'   => $this->faker->paragraph,
            'variants'      => $this->generateVariants(),
        ];
    }

    private function generateOptions()
    {
        return collect($this->faker->unique()->randomElements([
            [
                'name'     => $this->faker->word,
                'id'       => 'gid://shopify/ProductOption/'.$this->faker->randomNumber(14),
                'position' => $this->faker->numberBetween(1, 10),
                'values'   => $this->faker->words(),
            ],
            [
                'name'     => $this->faker->word,
                'id'       => 'gid://shopify/ProductOption/'.$this->faker->randomNumber(14),
                'position' => $this->faker->numberBetween(1, 10),
                'values'   => $this->faker->words(),
            ],
        ]))->toArray();
    }

    private function generateMetafields()
    {
        return collect([
            'nodes' => $this->faker->unique()->randomElements([
                [
                    'id'        => 'gid://shopify/Metafield/'.$this->faker->randomNumber(14),
                    'key'       => Str::of($this->faker->words)->kebab(),
                    'value'     => '{\"value\":42.0,\"unit\":\"MILLIMETERS\"}',
                    'jsonValue' => [
                        'value' => 42,
                        'unit'  => 'MILLIMETERS',
                    ],
                ],
                [
                    'id'        => 'gid://shopify/Metafield/'.$this->faker->randomNumber(14),
                    'key'       => Str::of($this->faker->words)->kebab(),
                    'value'     => $this->faker->words,
                    'jsonValue' => $this->faker->words,
                ],
            ]),
        ])->toArray();
    }

    private function generateVariants()
    {
        return collect([
            'pageInfo' => [
                'endCursor'   => base64_encode('{"last_id":49106668618016,"last_value":"3"}'),
                'startCursor' => base64_encode('{"last_id":49106668618016,"last_value":"3"}'),
            ],
            'nodes'    => $this->faker->unique()->randomElements([
                [
                    'id' => 'gid://shopify/ProductVariant/'.$this->faker->randomNumber(14),

                    'title'       => $this->faker->word.' / '.$this->faker->word,
                    'displayName' => $this->faker->words.' '.$this->faker->word.' / '.$this->faker->word,
                    'price'       => $this->faker->randomFloat(2, 1, 100),
                ],
                [
                    'id' => 'gid://shopify/ProductVariant/'.$this->faker->randomNumber(14),

                    'title'       => $this->faker->word.' / '.$this->faker->word,
                    'displayName' => $this->faker->words.' '.$this->faker->word.' / '.$this->faker->word,
                    'price'       => $this->faker->randomFloat(2, 1, 100),
                ],
            ]),
        ])->toArray();
    }
}
