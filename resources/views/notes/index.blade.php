<!-- notes/index.blade.php -->
@extends('layouts.app')
@section('content')
    <h1>Notes</h1>
    <a href="{{ route('notes.create') }}">Create Note</a>
    <ul>
        @foreach($notes as $note)
            <li><a href="{{ route('notes.show', $note) }}">{{ $note->title }}</a></li>
        @endforeach
    </ul>
@endsection
