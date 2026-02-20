<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition()
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph,
            'tasks' => null,
            'completed' => $this->faker->numberBetween(0, 100),
            'members' => [$this->faker->name, $this->faker->name],
            'status' => $this->faker->randomElement(['active', 'completed', 'archived']),
            'dueDate' => $this->faker->date(),
            'createdAt' => $this->faker->date(),
        ];
    }
}
