@extends('layouts.api-docs')
@section('title', 'Contact API Documentation')
@section('content')
<div class=" mx-auto py-10">
    <h1 class="text-3xl font-bold mb-6 text-[#BA1A1A]">Contact API Endpoints</h1>
    <div class="space-y-8">
        <x-api-endpoint method="POST" url="/api/contact" summary="Submit a contact form">
            <x-api-body :fields="[
                ['name' => 'name', 'type' => 'string'],
                ['name' => 'email', 'type' => 'string'],
                ['name' => 'message', 'type' => 'string'],
                ['name' => 'phone', 'type' => 'string'],
                ['name' => 'subject', 'type' => 'string'],
            ]" />
        </x-api-endpoint>
    </div>
</div>
@endsection
