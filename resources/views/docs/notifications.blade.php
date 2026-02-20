@extends('layouts.api-docs')
@section('title', 'Notifications API Documentation')
@section('content')
<div class=" mx-auto py-10">
    <h1 class="text-3xl font-bold mb-6 text-[#BA1A1A]">Notifications API Endpoints</h1>
    <div class="space-y-8">
        <x-api-endpoint method="GET" url="/api/notifications" summary="List all notifications" />
        <x-api-endpoint method="POST" url="/api/notifications" summary="Create a new notification">
            <x-api-body :fields="[
                ['name' => 'type', 'type' => 'string', 'required' => true],
                ['name' => 'title', 'type' => 'string', 'required' => true],
                ['name' => 'message', 'type' => 'string', 'required' => true],
                ['name' => 'read', 'type' => 'boolean'],
                ['name' => 'createdAt', 'type' => 'date'],
                ['name' => 'link', 'type' => 'string'],
                ['name' => 'actor', 'type' => 'array'],
            ]" />
        </x-api-endpoint>
        <x-api-endpoint method="GET" url="/api/notifications/{notification}" summary="Get a notification by ID" />
        <x-api-endpoint method="PUT" url="/api/notifications/{notification}" summary="Update a notification by ID">
            <x-api-body :fields="[
                ['name' => 'type', 'type' => 'string', 'required' => true],
                ['name' => 'title', 'type' => 'string', 'required' => true],
                ['name' => 'message', 'type' => 'string', 'required' => true],
                ['name' => 'read', 'type' => 'boolean'],
                ['name' => 'createdAt', 'type' => 'date'],
                ['name' => 'link', 'type' => 'string'],
                ['name' => 'actor', 'type' => 'array'],
            ]" />
        </x-api-endpoint>
        <x-api-endpoint method="DELETE" url="/api/notifications/{notification}" summary="Delete a notification by ID" />
    </div>
</div>
@endsection
