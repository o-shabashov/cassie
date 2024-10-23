<?php

namespace Database\Factories;

use App\Enums\UserPlatforms;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'              => fake()->name(),
            'remember_token'    => Str::random(10),
            'platform'          => UserPlatforms::shopify,
            'settings'          => [],
        ];
    }
}
