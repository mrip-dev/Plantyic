<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'time' => $this->faker->time(),
            'duration' => $this->faker->randomElement(['1h', '2h', '4h', '8h']),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'tags' => [$this->faker->word, $this->faker->word],
            'status' => $this->faker->randomElement(['pending', 'in_progress', 'completed']),
            'date' => $this->faker->date(),
            'assignee' => $this->faker->name,
            'project' => $this->faker->word,
        ];
    }
}
