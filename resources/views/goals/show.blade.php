<!-- goals/show.blade.php -->
@extends('layouts.app')
@section('content')
    <h1>{{ $goal->title }}</h1>
    <p>{{ $goal->description }}</p>
    <p>Progress: {{ $goal->progress }}%</p>
    <p>Target: {{ $goal->target }}</p>
    <p>Category: {{ $goal->category }}</p>
    <p>Due Date: {{ $goal->dueDate }}</p>
    <a href="{{ route('goals.edit', $goal) }}">Edit</a>
    <form method="POST" action="{{ route('goals.destroy', $goal) }}">
        @csrf
        @method('DELETE')
        <button type="submit">Delete</button>
    </form>
@endsection
