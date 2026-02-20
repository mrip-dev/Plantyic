@extends('layouts.api-docs')
@section('title', 'CKEditor API Documentation')
@section('content')
<div class=" mx-auto py-10">
    <h1 class="text-3xl font-bold mb-6 text-[#BA1A1A]">CKEditor API Endpoints</h1>
    <div class="space-y-8">
        <x-api-endpoint method="POST" url="/api/ckeditor/upload" summary="Upload image via CKEditor">
            <x-api-body :fields="[
                ['name' => 'upload', 'type' => 'file'],
            ]" />
        </x-api-endpoint>
    </div>
</div>
@endsection
