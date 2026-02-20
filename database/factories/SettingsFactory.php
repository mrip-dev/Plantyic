<?php

namespace Database\Factories;

use App\Models\Settings;
use Illuminate\Database\Eloquent\Factories\Factory;

class SettingsFactory extends Factory
{
    protected $model = Settings::class;

    public function definition()
    {
        return [
            'theme' => $this->faker->randomElement(['light', 'dark']),
            'primaryColor' => $this->faker->safeColorName,
            'fontFamily' => $this->faker->word,
            'displayFont' => $this->faker->word,
            'borderRadius' => $this->faker->randomElement(['sm', 'md', 'lg']),
            'sidebarCompact' => $this->faker->boolean,
            'animationsEnabled' => $this->faker->boolean,
            'fontSize' => $this->faker->randomElement(['sm', 'md', 'lg']),
            'language' => $this->faker->languageCode,
            'dateFormat' => 'Y-m-d',
            'timeFormat' => 'H:i',
            'weekStartsOn' => $this->faker->randomElement(['monday', 'sunday']),
            'emailNotifications' => $this->faker->boolean,
            'pushNotifications' => $this->faker->boolean,
            'taskReminders' => $this->faker->boolean,
            'weeklyDigest' => $this->faker->boolean,
            'teamUpdates' => $this->faker->boolean,
            'soundEnabled' => $this->faker->boolean,
            'sidebarOnRight' => $this->faker->boolean,
            'showBottomBar' => $this->faker->boolean,
        ];
    }
}
