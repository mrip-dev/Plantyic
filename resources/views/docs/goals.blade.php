@extends('layouts.api-docs')
@section('title', 'Goals API Documentation')
@section('content')
<div class=" mx-auto py-10">
    <h1 class="text-3xl font-bold mb-6 text-[#BA1A1A]">Goals API Endpoints</h1>
    <div class="space-y-8">
        <x-api-endpoint method="GET" url="/api/goals" summary="List all goals" />
        <x-api-endpoint method="POST" url="/api/goals" summary="Create a new goal">
            <x-api-body :fields="[
                ['name' => 'title', 'type' => 'string', 'required' => true],
                ['name' => 'description', 'type' => 'string'],
                ['name' => 'progress', 'type' => 'integer', 'required' => true],
                ['name' => 'target', 'type' => 'string'],
                ['name' => 'category', 'type' => 'string'],
                ['name' => 'dueDate', 'type' => 'date'],
                ['name' => 'milestones', 'type' => 'array'],
            ]" />
        </x-api-endpoint>
        <x-api-endpoint method="GET" url="/api/goals/{goal}" summary="Get a goal by ID" />
        <x-api-endpoint method="PUT" url="/api/goals/{goal}" summary="Update a goal by ID">
            <x-api-body :fields="[
                ['name' => 'title', 'type' => 'string', 'required' => true],
                ['name' => 'description', 'type' => 'string'],
                ['name' => 'progress', 'type' => 'integer', 'required' => true],
                ['name' => 'target', 'type' => 'string'],
                ['name' => 'category', 'type' => 'string'],
                ['name' => 'dueDate', 'type' => 'date'],
                ['name' => 'milestones', 'type' => 'array'],
            ]" />
        </x-api-endpoint>
        <x-api-endpoint method="DELETE" url="/api/goals/{goal}" summary="Delete a goal by ID" />
    </div>
</div>
@endsection
