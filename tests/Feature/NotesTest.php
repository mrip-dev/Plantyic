<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Note;

class NotesTest extends TestCase
{
    use RefreshDatabase;

    public function test_notes_index_returns_success()
    {
        Note::factory()->count(2)->create();
        $response = $this->getJson('/api/notes');
        $response->assertStatus(200)
                 ->assertJsonStructure(['data']);
    }

    public function test_notes_store_validates_input()
    {
        $response = $this->postJson('/api/notes', []);
        $response->assertStatus(422);
    }

    public function test_notes_can_be_created()
    {
        $payload = [
            'title' => 'Test Note',
            'content' => 'Test content',
            'tags' => ['api', 'test'],
            'pinned' => false,
            'createdAt' => '2026-02-20',
            'updatedAt' => '2026-02-20',
            'folder' => 'General',
        ];
        $response = $this->postJson('/api/notes', $payload);
        $response->assertStatus(201)
                 ->assertJsonFragment(['title' => 'Test Note']);
    }

    public function test_notes_can_be_shown()
    {
        $note = Note::factory()->create();
        $response = $this->getJson("/api/notes/{$note->id}");
        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => $note->title]);
    }

    public function test_notes_can_be_updated()
    {
        $note = Note::factory()->create();
        $payload = ['title' => 'Updated Note'];
        $response = $this->putJson("/api/notes/{$note->id}", $payload);
        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => 'Updated Note']);
    }

    public function test_notes_can_be_deleted()
    {
        $note = Note::factory()->create();
        $response = $this->deleteJson("/api/notes/{$note->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('notes', ['id' => $note->id]);
    }
}
