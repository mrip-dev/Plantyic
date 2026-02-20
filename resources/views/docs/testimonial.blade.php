@extends('layouts.api-docs')
@section('title', 'Testimonial API Documentation')
@section('content')
<div class=" mx-auto py-10">
    <h1 class="text-3xl font-bold mb-6 text-[#BA1A1A]">Testimonial API Endpoints</h1>
    <div class="space-y-8">
        <x-api-endpoint method="GET" url="/api/testimonials" summary="List all testimonials" />
        <x-api-endpoint method="POST" url="/api/testimonials" summary="Create a new testimonial">
            <x-api-body :fields="[
                ['name' => 'user_id', 'type' => 'integer'],
                ['name' => 'content', 'type' => 'string'],
                ['name' => 'rating', 'type' => 'integer'],
                ['name' => 'status', 'type' => 'string'],
            ]" />
        </x-api-endpoint>
        <x-api-endpoint method="GET" url="/api/testimonials/{testimonial}" summary="Get testimonial by ID" />
        <x-api-endpoint method="PUT" url="/api/testimonials/{testimonial}" summary="Update testimonial by ID">
            <x-api-body :fields="[
                ['name' => 'content', 'type' => 'string'],
                ['name' => 'rating', 'type' => 'integer'],
                ['name' => 'status', 'type' => 'string'],
            ]" />
        </x-api-endpoint>
        <x-api-endpoint method="DELETE" url="/api/testimonials/{testimonial}" summary="Delete testimonial by ID" />
    </div>
</div>
@endsection
