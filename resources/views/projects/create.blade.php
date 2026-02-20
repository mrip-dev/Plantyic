<!-- projects/create.blade.php -->
@extends('layouts.app')
@section('content')
    <h1>Create Project</h1>
    <form method="POST" action="{{ route('projects.store') }}">
        @csrf
        <input type="text" name="name" placeholder="Name" required>
        <textarea name="description" placeholder="Description"></textarea>
        <input type="number" name="tasks" placeholder="Total Tasks">
        <input type="number" name="completed" placeholder="Completed Tasks">
        <input type="text" name="members" placeholder="Members (comma separated)">
        <select name="status">
            <option value="active">Active</option>
            <option value="on-hold">On Hold</option>
            <option value="completed">Completed</option>
        </select>
        <input type="date" name="dueDate">
        <input type="date" name="createdAt">
        <button type="submit">Save</button>
    </form>
@endsection
