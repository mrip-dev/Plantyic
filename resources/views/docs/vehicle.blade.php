@extends('layouts.api-docs')
@section('title', 'Vehicle API Documentation')
@section('content')
<div class=" mx-auto py-10">
    <h1 class="text-3xl font-bold mb-6 text-[#BA1A1A]">Vehicle API Endpoints</h1>
    <div class="space-y-8">
        <x-api-endpoint method="GET" url="/api/vehicles" summary="List all vehicles" />
        <x-api-endpoint method="POST" url="/api/vehicles" summary="Create a new vehicle">
            <x-api-body :fields="[
                ['name' => 'make', 'type' => 'string'],
                ['name' => 'model', 'type' => 'string'],
                ['name' => 'year', 'type' => 'integer'],
                ['name' => 'vin', 'type' => 'string'],
                ['name' => 'color', 'type' => 'string'],
                ['name' => 'mileage', 'type' => 'integer'],
                ['name' => 'engine_displacement', 'type' => 'string'],
                ['name' => 'transmission', 'type' => 'string'],
                ['name' => 'fuel_type', 'type' => 'string'],
                ['name' => 'registration_number', 'type' => 'string'],
                ['name' => 'owner_id', 'type' => 'integer'],
                ['name' => 'status', 'type' => 'string'],
            ]" />
        </x-api-endpoint>
        <x-api-endpoint method="GET" url="/api/vehicles/{vehicle}" summary="Get vehicle by ID" />
        <x-api-endpoint method="PUT" url="/api/vehicles/{vehicle}" summary="Update vehicle by ID">
            <x-api-body :fields="[
                ['name' => 'make', 'type' => 'string'],
                ['name' => 'model', 'type' => 'string'],
                ['name' => 'year', 'type' => 'integer'],
                ['name' => 'vin', 'type' => 'string'],
                ['name' => 'color', 'type' => 'string'],
                ['name' => 'mileage', 'type' => 'integer'],
                ['name' => 'engine_displacement', 'type' => 'string'],
                ['name' => 'transmission', 'type' => 'string'],
                ['name' => 'fuel_type', 'type' => 'string'],
                ['name' => 'registration_number', 'type' => 'string'],
                ['name' => 'owner_id', 'type' => 'integer'],
                ['name' => 'status', 'type' => 'string'],
            ]" />
        </x-api-endpoint>
        <x-api-endpoint method="DELETE" url="/api/vehicles/{vehicle}" summary="Delete vehicle by ID" />
    </div>
</div>
@endsection
