<!-- projects/edit.blade.php -->
@extends('layouts.app')
@section('content')
    <h1>Edit Project</h1>
    <form method="POST" action="{{ route('projects.update', $project) }}">
        @csrf
        @method('PUT')
        <input type="text" name="name" value="{{ $project->name }}" required>
        <textarea name="description">{{ $project->description }}</textarea>
        <input type="number" name="tasks" value="{{ $project->tasks }}">
        <input type="number" name="completed" value="{{ $project->completed }}">
        <input type="text" name="members" value="{{ is_array($project->members) ? implode(',', $project->members) : $project->members }}">
        <select name="status">
            <option value="active" @if($project->status=='active') selected @endif>Active</option>
            <option value="on-hold" @if($project->status=='on-hold') selected @endif>On Hold</option>
            <option value="completed" @if($project->status=='completed') selected @endif>Completed</option>
        </select>
        <input type="date" name="dueDate" value="{{ $project->dueDate ? $project->dueDate->format('Y-m-d') : '' }}">
        <input type="date" name="createdAt" value="{{ $project->createdAt ? $project->createdAt->format('Y-m-d') : '' }}">
        <button type="submit">Update</button>
    </form>
@endsection
