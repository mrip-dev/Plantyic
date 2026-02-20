<?php

namespace Database\Factories;

use App\Models\Integration;
use Illuminate\Database\Eloquent\Factories\Factory;

class IntegrationFactory extends Factory
{
    protected $model = Integration::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'icon' => $this->faker->imageUrl(64, 64, 'business'),
            'connected' => $this->faker->boolean,
            'scopes' => $this->faker->randomElements(['read', 'write', 'delete'], $this->faker->numberBetween(1, 3)),
            'lastSync' => $this->faker->dateTimeThisYear,
        ];
    }
}
