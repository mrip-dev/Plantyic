@extends('layouts.api-docs')
@section('title', 'Team API Documentation')
@section('content')
<div class=" mx-auto py-10">
    <h1 class="text-3xl font-bold mb-6 text-[#BA1A1A]">Team API Endpoints</h1>
    <div class="space-y-8">
        <x-api-endpoint method="GET" url="/api/team" summary="List all teams" />
        <x-api-endpoint method="POST" url="/api/team" summary="Create a new team">
            <x-api-body :fields="[
                ['name' => 'name', 'type' => 'string', 'required' => true],
                ['name' => 'members', 'type' => 'array'],
                ['name' => 'color', 'type' => 'string'],
            ]" />
        </x-api-endpoint>
        <x-api-endpoint method="GET" url="/api/team/{team}" summary="Get a team by ID" />
        <x-api-endpoint method="PUT" url="/api/team/{team}" summary="Update a team by ID">
            <x-api-body :fields="[
                ['name' => 'name', 'type' => 'string', 'required' => true],
                ['name' => 'members', 'type' => 'array'],
                ['name' => 'color', 'type' => 'string'],
            ]" />
        </x-api-endpoint>
        <x-api-endpoint method="DELETE" url="/api/team/{team}" summary="Delete a team by ID" />
    </div>
</div>
@endsection
