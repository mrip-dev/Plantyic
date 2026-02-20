@extends('layouts.api-docs')
@section('title', 'User API Documentation')
@section('content')
<div class=" mx-auto py-10">
    <h1 class="text-3xl font-bold mb-6 text-[#BA1A1A]">User API Endpoints</h1>
    <div class="space-y-8">
        <x-api-endpoint method="GET" url="/api/users" summary="List all users" />
        <x-api-endpoint method="POST" url="/api/users" summary="Create a new user">
            <x-api-body :fields="[
                ['name' => 'name', 'type' => 'string'],
                ['name' => 'email', 'type' => 'string'],
                ['name' => 'password', 'type' => 'string'],
                ['name' => 'role', 'type' => 'string'],
                ['name' => 'phone', 'type' => 'string'],
                ['name' => 'avatar', 'type' => 'string'],
                ['name' => 'status', 'type' => 'string'],
            ]" />
        </x-api-endpoint>
        <x-api-endpoint method="GET" url="/api/users/{user}" summary="Get user by ID" />
        <x-api-endpoint method="PUT" url="/api/users/{user}" summary="Update user by ID">
            <x-api-body :fields="[
                ['name' => 'name', 'type' => 'string'],
                ['name' => 'email', 'type' => 'string'],
                ['name' => 'password', 'type' => 'string'],
                ['name' => 'role', 'type' => 'string'],
                ['name' => 'phone', 'type' => 'string'],
                ['name' => 'avatar', 'type' => 'string'],
                ['name' => 'status', 'type' => 'string'],
            ]" />
        </x-api-endpoint>
        <x-api-endpoint method="DELETE" url="/api/users/{user}" summary="Delete user by ID" />
    </div>
</div>
@endsection
