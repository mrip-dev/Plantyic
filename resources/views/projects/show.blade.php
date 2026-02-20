<!-- projects/show.blade.php -->
@extends('layouts.app')
@section('content')
    <h1>{{ $project->name }}</h1>
    <p>{{ $project->description }}</p>
    <p>Status: {{ $project->status }}</p>
    <p>Due Date: {{ $project->dueDate }}</p>
    <a href="{{ route('projects.edit', $project) }}">Edit</a>
    <form method="POST" action="{{ route('projects.destroy', $project) }}">
        @csrf
        @method('DELETE')
        <button type="submit">Delete</button>
    </form>
@endsection
