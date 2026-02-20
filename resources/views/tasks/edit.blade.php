<!-- tasks/edit.blade.php -->
@extends('layouts.app')
@section('content')
    <h1>Edit Task</h1>
    <form method="POST" action="{{ route('tasks.update', $task) }}">
        @csrf
        @method('PUT')
        <input type="text" name="title" value="{{ $task->title }}" required>
        <textarea name="description">{{ $task->description }}</textarea>
        <input type="text" name="time" value="{{ $task->time }}">
        <input type="text" name="duration" value="{{ $task->duration }}">
        <select name="priority">
            <option value="low" @if($task->priority=='low') selected @endif>Low</option>
            <option value="medium" @if($task->priority=='medium') selected @endif>Medium</option>
            <option value="high" @if($task->priority=='high') selected @endif>High</option>
        </select>
        <input type="text" name="tags" value="{{ is_array($task->tags) ? implode(',', $task->tags) : $task->tags }}">
        <select name="status">
            <option value="todo" @if($task->status=='todo') selected @endif>To Do</option>
            <option value="in-progress" @if($task->status=='in-progress') selected @endif>In Progress</option>
            <option value="done" @if($task->status=='done') selected @endif>Done</option>
        </select>
        <input type="date" name="date" value="{{ $task->date->format('Y-m-d') }}" required>
        <input type="text" name="assignee" value="{{ $task->assignee }}">
        <input type="text" name="project" value="{{ $task->project }}">
        <button type="submit">Update</button>
    </form>
@endsection
