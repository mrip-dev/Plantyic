<?php

namespace Database\Factories;

use App\Models\Workspace;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkspaceFactory extends Factory
{
    protected $model = Workspace::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'description' => $this->faker->sentence,
            'icon' => $this->faker->imageUrl(64, 64, 'business'),
            'color' => $this->faker->safeColorName,
            'plan' => $this->faker->randomElement(['free', 'pro', 'enterprise']),
        ];
    }
}
