<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamFactory extends Factory
{
    protected $model = Team::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'members' => $this->faker->randomElements([
                $this->faker->name,
                $this->faker->name,
                $this->faker->name
            ], $this->faker->numberBetween(1, 5)),
            'color' => $this->faker->safeColorName,
        ];
    }
}
