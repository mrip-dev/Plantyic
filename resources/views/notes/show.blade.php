<!-- notes/show.blade.php -->
@extends('layouts.app')
@section('content')
    <h1>{{ $note->title }}</h1>
    <p>{{ $note->content }}</p>
    <p>Pinned: {{ $note->pinned ? 'Yes' : 'No' }}</p>
    <a href="{{ route('notes.edit', $note) }}">Edit</a>
    <form method="POST" action="{{ route('notes.destroy', $note) }}">
        @csrf
        @method('DELETE')
        <button type="submit">Delete</button>
    </form>
@endsection
