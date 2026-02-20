<!-- tasks/show.blade.php -->
@extends('layouts.app')
@section('content')
    <h1>{{ $task->title }}</h1>
    <p>{{ $task->description }}</p>
    <p>Status: {{ $task->status }}</p>
    <p>Priority: {{ $task->priority }}</p>
    <p>Date: {{ $task->date }}</p>
    <a href="{{ route('tasks.edit', $task) }}">Edit</a>
    <form method="POST" action="{{ route('tasks.destroy', $task) }}">
        @csrf
        @method('DELETE')
        <button type="submit">Delete</button>
    </form>
@endsection
