<!-- tasks/index.blade.php -->
@extends('layouts.app')
@section('content')
    <h1>Tasks</h1>
    <a href="{{ route('tasks.create') }}">Create Task</a>
    <ul>
        @foreach($tasks as $task)
            <li><a href="{{ route('tasks.show', $task) }}">{{ $task->title }}</a></li>
        @endforeach
    </ul>
@endsection
