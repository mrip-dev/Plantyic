<?php

namespace Database\Factories;

use App\Models\Note;
use Illuminate\Database\Eloquent\Factories\Factory;

class NoteFactory extends Factory
{
    protected $model = Note::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'content' => $this->faker->paragraph,
            'tags' => [$this->faker->word, $this->faker->word],
            'pinned' => $this->faker->boolean,
            'createdAt' => $this->faker->date(),
            'updatedAt' => $this->faker->date(),
            'folder' => $this->faker->word,
        ];
    }
}
