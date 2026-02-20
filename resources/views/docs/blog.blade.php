@extends('layouts.api-docs')
@section('title', 'Blog API Documentation')
@section('content')
<div class=" mx-auto py-10">
    <h1 class="text-3xl font-bold mb-6 text-[#BA1A1A]">Blog API Endpoints</h1>
    <div class="space-y-8">
        <x-api-endpoint method="GET" url="/api/blogs" summary="List all blogs" />
        <x-api-endpoint method="POST" url="/api/blogs" summary="Create a new blog">
            <x-api-body :fields="[
                ['name' => 'title', 'type' => 'string'],
                ['name' => 'content', 'type' => 'string'],
                ['name' => 'author_id', 'type' => 'integer'],
                ['name' => 'status', 'type' => 'string'],
                ['name' => 'tags', 'type' => 'array'],
                ['name' => 'cover_image', 'type' => 'string'],
            ]" />
        </x-api-endpoint>
        <x-api-endpoint method="GET" url="/api/blogs/{blog}" summary="Get blog by ID" />
        <x-api-endpoint method="PUT" url="/api/blogs/{blog}" summary="Update blog by ID">
            <x-api-body :fields="[
                ['name' => 'title', 'type' => 'string'],
                ['name' => 'content', 'type' => 'string'],
                ['name' => 'status', 'type' => 'string'],
                ['name' => 'tags', 'type' => 'array'],
                ['name' => 'cover_image', 'type' => 'string'],
            ]" />
        </x-api-endpoint>
        <x-api-endpoint method="DELETE" url="/api/blogs/{blog}" summary="Delete blog by ID" />
        <x-api-endpoint method="GET" url="/api/blogs/{blog}/comments" summary="List comments for a blog" />
    </div>
</div>
@endsection
