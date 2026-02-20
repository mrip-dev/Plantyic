<?php

namespace Database\Factories;

use App\Models\Goal;
use Illuminate\Database\Eloquent\Factories\Factory;

class GoalFactory extends Factory
{
    protected $model = Goal::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'progress' => $this->faker->numberBetween(0, 100),
            'target' => $this->faker->word,
            'category' => $this->faker->word,
            'dueDate' => $this->faker->date(),
            'milestones' => [$this->faker->word, $this->faker->word],
        ];
    }
}
