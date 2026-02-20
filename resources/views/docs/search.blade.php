@extends('layouts.api-docs')
@section('title', 'Global Search API Documentation')
@section('content')
<div class=" mx-auto py-10">
    <h1 class="text-3xl font-bold mb-6 text-[#BA1A1A]">Global Search API Endpoints</h1>
    <div class="space-y-8">
        <x-api-endpoint method="GET" url="/api/search" summary="Search across all modules">
            <x-api-body :fields="[
                ['name' => 'query', 'type' => 'string'],
                ['name' => 'modules', 'type' => 'array'],
            ]" />
        </x-api-endpoint>
    </div>
</div>
@endsection
