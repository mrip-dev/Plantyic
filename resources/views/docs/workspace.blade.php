@extends('layouts.api-docs')
@section('title', 'Workspace API Documentation')
@section('content')
<div class=" mx-auto py-10">
    <h1 class="text-3xl font-bold mb-6 text-[#BA1A1A]">Workspace API Endpoints</h1>
    <div class="space-y-8">
        <x-api-endpoint method="GET" url="/api/workspace" summary="List all workspaces" />
        <x-api-endpoint method="POST" url="/api/workspace" summary="Create a new workspace">
            <x-api-body :fields="[
                ['name' => 'name', 'type' => 'string', 'required' => true],
                ['name' => 'description', 'type' => 'string'],
                ['name' => 'icon', 'type' => 'string'],
                ['name' => 'color', 'type' => 'string'],
                ['name' => 'plan', 'type' => 'string'],
            ]" />
        </x-api-endpoint>
        <x-api-endpoint method="GET" url="/api/workspace/{workspace}" summary="Get a workspace by ID" />
        <x-api-endpoint method="PUT" url="/api/workspace/{workspace}" summary="Update a workspace by ID">
            <x-api-body :fields="[
                ['name' => 'name', 'type' => 'string', 'required' => true],
                ['name' => 'description', 'type' => 'string'],
                ['name' => 'icon', 'type' => 'string'],
                ['name' => 'color', 'type' => 'string'],
                ['name' => 'plan', 'type' => 'string'],
            ]" />
        </x-api-endpoint>
        <x-api-endpoint method="DELETE" url="/api/workspace/{workspace}" summary="Delete a workspace by ID" />
    </div>
</div>
@endsection
