@extends('layouts.api-docs')
@section('title', 'Tasks API Documentation')
@section('content')
<div class=" mx-auto py-10">
    <h1 class="text-3xl font-bold mb-6 text-[#BA1A1A]">Tasks API Endpoints</h1>
    <div class="space-y-8">
        <x-api-endpoint method="GET" url="/api/tasks" summary="List all tasks" />
        <x-api-endpoint method="POST" url="/api/tasks" summary="Create a new task">
            <x-api-body :fields="[
                ['name' => 'title', 'type' => 'string', 'required' => true],
                ['name' => 'description', 'type' => 'string'],
                ['name' => 'time', 'type' => 'string'],
                ['name' => 'duration', 'type' => 'string'],
                ['name' => 'priority', 'type' => 'string', 'required' => true],
                ['name' => 'tags', 'type' => 'array'],
                ['name' => 'status', 'type' => 'string', 'required' => true],
                ['name' => 'date', 'type' => 'date', 'required' => true],
                ['name' => 'assignee', 'type' => 'string'],
                ['name' => 'project', 'type' => 'string'],
            ]" />
        </x-api-endpoint>
        <x-api-endpoint method="GET" url="/api/tasks/{task}" summary="Get a task by ID" />
        <x-api-endpoint method="PUT" url="/api/tasks/{task}" summary="Update a task by ID">
            <x-api-body :fields="[
                ['name' => 'title', 'type' => 'string', 'required' => true],
                ['name' => 'description', 'type' => 'string'],
                ['name' => 'time', 'type' => 'string'],
                ['name' => 'duration', 'type' => 'string'],
                ['name' => 'priority', 'type' => 'string', 'required' => true],
                ['name' => 'tags', 'type' => 'array'],
                ['name' => 'status', 'type' => 'string', 'required' => true],
                ['name' => 'date', 'type' => 'date', 'required' => true],
                ['name' => 'assignee', 'type' => 'string'],
                ['name' => 'project', 'type' => 'string'],
            ]" />
        </x-api-endpoint>
        <x-api-endpoint method="DELETE" url="/api/tasks/{task}" summary="Delete a task by ID" />
    </div>
</div>
@endsection
