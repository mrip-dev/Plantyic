@extends('layouts.api-docs')
@section('title', 'Integrations API Documentation')
@section('content')
<div class=" mx-auto py-10">
    <h1 class="text-3xl font-bold mb-6 text-[#BA1A1A]">Integrations API Endpoints</h1>
    <div class="space-y-8">
        <x-api-endpoint method="GET" url="/api/integrations" summary="List all integrations" />
        <x-api-endpoint method="POST" url="/api/integrations" summary="Create a new integration">
            <x-api-body :fields="[
                ['name' => 'name', 'type' => 'string', 'required' => true],
                ['name' => 'description', 'type' => 'string'],
                ['name' => 'icon', 'type' => 'string'],
                ['name' => 'connected', 'type' => 'boolean'],
                ['name' => 'scopes', 'type' => 'array'],
                ['name' => 'lastSync', 'type' => 'date'],
            ]" />
        </x-api-endpoint>
        <x-api-endpoint method="GET" url="/api/integrations/{integration}" summary="Get an integration by ID" />
        <x-api-endpoint method="PUT" url="/api/integrations/{integration}" summary="Update an integration by ID">
            <x-api-body :fields="[
                ['name' => 'name', 'type' => 'string', 'required' => true],
                ['name' => 'description', 'type' => 'string'],
                ['name' => 'icon', 'type' => 'string'],
                ['name' => 'connected', 'type' => 'boolean'],
                ['name' => 'scopes', 'type' => 'array'],
                ['name' => 'lastSync', 'type' => 'date'],
            ]" />
        </x-api-endpoint>
        <x-api-endpoint method="DELETE" url="/api/integrations/{integration}" summary="Delete an integration by ID" />
    </div>
</div>
@endsection
