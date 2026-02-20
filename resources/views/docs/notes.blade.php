@extends('layouts.api-docs')
@section('title', 'Notes API Documentation')
@section('content')
<div class=" mx-auto py-10">
    <h1 class="text-3xl font-bold mb-6 text-[#BA1A1A]">Notes API Endpoints</h1>
    <div class="space-y-8">
        <x-api-endpoint method="GET" url="/api/notes" summary="List all notes" />
        <x-api-endpoint method="POST" url="/api/notes" summary="Create a new note">
            <x-api-body :fields="[
                ['name' => 'title', 'type' => 'string', 'required' => true],
                ['name' => 'content', 'type' => 'string'],
                ['name' => 'tags', 'type' => 'array'],
                ['name' => 'pinned', 'type' => 'boolean'],
                ['name' => 'createdAt', 'type' => 'date'],
                ['name' => 'updatedAt', 'type' => 'date'],
                ['name' => 'folder', 'type' => 'string'],
            ]" />
        </x-api-endpoint>
        <x-api-endpoint method="GET" url="/api/notes/{note}" summary="Get a note by ID" />
        <x-api-endpoint method="PUT" url="/api/notes/{note}" summary="Update a note by ID">
            <x-api-body :fields="[
                ['name' => 'title', 'type' => 'string', 'required' => true],
                ['name' => 'content', 'type' => 'string'],
                ['name' => 'tags', 'type' => 'array'],
                ['name' => 'pinned', 'type' => 'boolean'],
                ['name' => 'createdAt', 'type' => 'date'],
                ['name' => 'updatedAt', 'type' => 'date'],
                ['name' => 'folder', 'type' => 'string'],
            ]" />
        </x-api-endpoint>
        <x-api-endpoint method="DELETE" url="/api/notes/{note}" summary="Delete a note by ID" />
    </div>
</div>
@endsection
