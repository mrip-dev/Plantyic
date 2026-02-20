<!-- goals/create.blade.php -->
@extends('layouts.app')
@section('content')
    <h1>Create Goal</h1>
    <form method="POST" action="{{ route('goals.store') }}">
        @csrf
        <input type="text" name="title" placeholder="Title" required>
        <textarea name="description" placeholder="Description"></textarea>
        <input type="number" name="progress" placeholder="Progress (0-100)" min="0" max="100" required>
        <input type="text" name="target" placeholder="Target">
        <input type="text" name="category" placeholder="Category">
        <input type="date" name="dueDate">
        <textarea name="milestones" placeholder="Milestones (JSON array)"></textarea>
        <button type="submit">Save</button>
    </form>
@endsection
