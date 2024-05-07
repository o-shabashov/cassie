<?php

namespace Database\Factories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PageFactory extends Factory
{
    protected $model = Page::class;

    public function definition(): array
    {
        return [
            'title'      => $this->faker->word(),
            'url'        => $this->faker->url(),
            'sections'   => [$this->faker->words()],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
