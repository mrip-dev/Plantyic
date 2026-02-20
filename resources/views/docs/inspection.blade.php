@extends('layouts.api-docs')
@section('title', 'Inspection API Documentation')
@section('content')
<div class=" mx-auto py-10">
    <h1 class="text-3xl font-bold mb-6 text-[#BA1A1A]">Inspection API Endpoints</h1>
    <div class="space-y-8">
        <x-api-endpoint method="GET" url="/api/inspections" summary="List all inspections" />
        <x-api-endpoint method="POST" url="/api/inspections" summary="Create a new inspection">
            <x-api-body :fields="[
                ['name' => 'vehicle_id', 'type' => 'integer'],
                ['name' => 'inspector_id', 'type' => 'integer'],
                ['name' => 'date', 'type' => 'date'],
                ['name' => 'status', 'type' => 'string'],
                ['name' => 'report', 'type' => 'string'],
                ['name' => 'score', 'type' => 'integer'],
            ]" />
        </x-api-endpoint>
        <x-api-endpoint method="GET" url="/api/inspections/{inspection}" summary="Get inspection by ID" />
        <x-api-endpoint method="PUT" url="/api/inspections/{inspection}" summary="Update inspection by ID">
            <x-api-body :fields="[
                ['name' => 'status', 'type' => 'string'],
                ['name' => 'report', 'type' => 'string'],
                ['name' => 'score', 'type' => 'integer'],
            ]" />
        </x-api-endpoint>
        <x-api-endpoint method="DELETE" url="/api/inspections/{inspection}" summary="Delete inspection by ID" />
        <x-api-endpoint method="GET" url="/api/vehicles/{vehicle}/inspections" summary="List all inspections for a vehicle" />
    </div>
</div>
@endsection
