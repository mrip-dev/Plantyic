@extends('layouts.api-docs')
@section('title', 'Projects API Documentation')
@section('content')
<div class=" mx-auto py-10">
    <h1 class="text-3xl font-bold mb-6 text-[#BA1A1A]">Projects API Endpoints</h1>
    <div class="space-y-8">
        <x-api-endpoint method="GET" url="/api/projects" summary="List all projects" />
        <x-api-endpoint method="POST" url="/api/projects" summary="Create a new project">
            <x-api-body :fields="[
                ['name' => 'name', 'type' => 'string', 'required' => true],
                ['name' => 'description', 'type' => 'string'],
                ['name' => 'tasks', 'type' => 'integer'],
                ['name' => 'completed', 'type' => 'integer'],
                ['name' => 'members', 'type' => 'array'],
                ['name' => 'status', 'type' => 'string', 'required' => true],
                ['name' => 'dueDate', 'type' => 'date'],
                ['name' => 'createdAt', 'type' => 'date'],
            ]" />
        </x-api-endpoint>
        <x-api-endpoint method="GET" url="/api/projects/{project}" summary="Get a project by ID" />
        <x-api-endpoint method="PUT" url="/api/projects/{project}" summary="Update a project by ID">
            <x-api-body :fields="[
                ['name' => 'name', 'type' => 'string', 'required' => true],
                ['name' => 'description', 'type' => 'string'],
                ['name' => 'tasks', 'type' => 'integer'],
                ['name' => 'completed', 'type' => 'integer'],
                ['name' => 'members', 'type' => 'array'],
                ['name' => 'status', 'type' => 'string', 'required' => true],
                ['name' => 'dueDate', 'type' => 'date'],
                ['name' => 'createdAt', 'type' => 'date'],
            ]" />
        </x-api-endpoint>
        <x-api-endpoint method="DELETE" url="/api/projects/{project}" summary="Delete a project by ID" />
    </div>
</div>
@endsection
