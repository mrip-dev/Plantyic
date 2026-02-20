<!-- goals/edit.blade.php -->
@extends('layouts.app')
@section('content')
    <h1>Edit Goal</h1>
    <form method="POST" action="{{ route('goals.update', $goal) }}">
        @csrf
        @method('PUT')
        <input type="text" name="title" value="{{ $goal->title }}" required>
        <textarea name="description">{{ $goal->description }}</textarea>
        <input type="number" name="progress" value="{{ $goal->progress }}" min="0" max="100" required>
        <input type="text" name="target" value="{{ $goal->target }}">
        <input type="text" name="category" value="{{ $goal->category }}">
        <input type="date" name="dueDate" value="{{ $goal->dueDate ? $goal->dueDate->format('Y-m-d') : '' }}">
        <textarea name="milestones">{{ json_encode($goal->milestones) }}</textarea>
        <button type="submit">Update</button>
    </form>
@endsection
