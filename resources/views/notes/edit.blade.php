<!-- notes/edit.blade.php -->
@extends('layouts.app')
@section('content')
    <h1>Edit Note</h1>
    <form method="POST" action="{{ route('notes.update', $note) }}">
        @csrf
        @method('PUT')
        <input type="text" name="title" value="{{ $note->title }}" required>
        <textarea name="content">{{ $note->content }}</textarea>
        <input type="text" name="tags" value="{{ is_array($note->tags) ? implode(',', $note->tags) : $note->tags }}">
        <label><input type="checkbox" name="pinned" value="1" @if($note->pinned) checked @endif> Pinned</label>
        <input type="date" name="createdAt" value="{{ $note->createdAt ? $note->createdAt->format('Y-m-d') : '' }}">
        <input type="date" name="updatedAt" value="{{ $note->updatedAt ? $note->updatedAt->format('Y-m-d') : '' }}">
        <input type="text" name="folder" value="{{ $note->folder }}">
        <button type="submit">Update</button>
    </form>
@endsection
