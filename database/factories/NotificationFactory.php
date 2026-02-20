<?php

namespace Database\Factories;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition()
    {
        return [
            'id' => $this->faker->uuid,
            'type' => $this->faker->randomElement(['info', 'warning', 'error']),
            'title' => $this->faker->sentence,
            'message' => $this->faker->paragraph,
            'read' => $this->faker->boolean,
            'createdAt' => $this->faker->dateTimeThisYear,
            'link' => $this->faker->url,
            'actor' => [
                'id' => $this->faker->uuid,
                'name' => $this->faker->name,
                'avatar' => $this->faker->imageUrl(64, 64, 'people'),
            ],
        ];
    }
}
