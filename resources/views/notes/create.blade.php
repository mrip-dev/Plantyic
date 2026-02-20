<!-- notes/create.blade.php -->
@extends('layouts.app')
@section('content')
    <h1>Create Note</h1>
    <form method="POST" action="{{ route('notes.store') }}">
        @csrf
        <input type="text" name="title" placeholder="Title" required>
        <textarea name="content" placeholder="Content"></textarea>
        <input type="text" name="tags" placeholder="Tags (comma separated)">
        <label><input type="checkbox" name="pinned" value="1"> Pinned</label>
        <input type="date" name="createdAt">
        <input type="date" name="updatedAt">
        <input type="text" name="folder" placeholder="Folder">
        <button type="submit">Save</button>
    </form>
@endsection
