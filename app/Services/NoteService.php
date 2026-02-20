<?php

namespace App\Services;

use App\Models\Note;

class NoteService
{
    public function create(array $data): Note
    {
        return Note::create($data);
    }

    public function update(Note $note, array $data): Note
    {
        $note->update($data);
        return $note;
    }

    public function delete(Note $note): void
    {
        $note->delete();
    }
}
