<!-- tasks/create.blade.php -->
@extends('layouts.app')
@section('content')
    <h1>Create Task</h1>
    <form method="POST" action="{{ route('tasks.store') }}">
        @csrf
        <input type="text" name="title" placeholder="Title" required>
        <textarea name="description" placeholder="Description"></textarea>
        <input type="text" name="time" placeholder="Time">
        <input type="text" name="duration" placeholder="Duration">
        <select name="priority">
            <option value="low">Low</option>
            <option value="medium">Medium</option>
            <option value="high">High</option>
        </select>
        <input type="text" name="tags" placeholder="Tags (comma separated)">
        <select name="status">
            <option value="todo">To Do</option>
            <option value="in-progress">In Progress</option>
            <option value="done">Done</option>
        </select>
        <input type="date" name="date" required>
        <input type="text" name="assignee" placeholder="Assignee">
        <input type="text" name="project" placeholder="Project">
        <button type="submit">Save</button>
    </form>
@endsection
