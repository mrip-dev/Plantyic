<!-- goals/index.blade.php -->
@extends('layouts.app')
@section('content')
    <h1>Goals</h1>
    <a href="{{ route('goals.create') }}">Create Goal</a>
    <ul>
        @foreach($goals as $goal)
            <li><a href="{{ route('goals.show', $goal) }}">{{ $goal->title }}</a></li>
        @endforeach
    </ul>
@endsection
