@extends('layouts.api-docs')
@section('title', 'Favorite API Documentation')
@section('content')
<div class=" mx-auto py-10">
    <h1 class="text-3xl font-bold mb-6 text-[#BA1A1A]">Favorite API Endpoints</h1>
    <div class="space-y-8">
        <x-api-endpoint method="GET" url="/api/favorites" summary="List all favorites for user" />
        <x-api-endpoint method="POST" url="/api/favorites" summary="Add a vehicle to favorites">
            <x-api-body :fields="[
                ['name' => 'vehicle_id', 'type' => 'integer'],
            ]" />
        </x-api-endpoint>
        <x-api-endpoint method="DELETE" url="/api/favorites/{favorite}" summary="Remove a vehicle from favorites" />
    </div>
</div>
@endsection
