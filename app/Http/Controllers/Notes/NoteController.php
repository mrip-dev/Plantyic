<?php

namespace App\Http\Controllers\Notes;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Http\Requests\Notes\NoteRequest;
use App\Services\NoteService;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index()
    {
        $notes = Note::all();
        return response()->json(['data' => $notes]);
    }

    public function store(NoteRequest $request, NoteService $service)
    {
        $note = $service->create($request->validated());
        return response()->json(['data' => $note], 201);
    }

    public function show(Note $note)
    {
        return response()->json(['data' => $note]);
    }

    public function update(NoteRequest $request, Note $note, NoteService $service)
    {
        $service->update($note, $request->validated());
        return response()->json(['data' => $note]);
    }

    public function destroy(Note $note, NoteService $service)
    {
        $service->delete($note);
        return response()->json(['message' => 'Note deleted successfully']);
    }
}
