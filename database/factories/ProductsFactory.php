<?php

namespace Database\Factories;

use App\Models\Products;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ProductsFactory extends Factory
{
    protected $model = Products::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'title'      => $this->faker->word(),
            'fields'     => [$this->faker->words()],
            'url'        => $this->faker->url(),
        ];
    }
}
