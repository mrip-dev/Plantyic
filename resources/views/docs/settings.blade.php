@extends('layouts.api-docs')
@section('title', 'Settings API Documentation')
@section('content')
<div class=" mx-auto py-10">
    <h1 class="text-3xl font-bold mb-6 text-[#BA1A1A]">Settings API Endpoints</h1>
    <div class="space-y-8">
        <x-api-endpoint method="GET" url="/api/settings" summary="List all settings" />
        <x-api-endpoint method="POST" url="/api/settings" summary="Create new settings">
            <x-api-body :fields="[
                ['name' => 'theme', 'type' => 'string'],
                ['name' => 'primaryColor', 'type' => 'string'],
                ['name' => 'fontFamily', 'type' => 'string'],
                ['name' => 'displayFont', 'type' => 'string'],
                ['name' => 'borderRadius', 'type' => 'string'],
                ['name' => 'sidebarCompact', 'type' => 'boolean'],
                ['name' => 'animationsEnabled', 'type' => 'boolean'],
                ['name' => 'fontSize', 'type' => 'string'],
                ['name' => 'language', 'type' => 'string'],
                ['name' => 'dateFormat', 'type' => 'string'],
                ['name' => 'timeFormat', 'type' => 'string'],
                ['name' => 'weekStartsOn', 'type' => 'string'],
                ['name' => 'emailNotifications', 'type' => 'boolean'],
                ['name' => 'pushNotifications', 'type' => 'boolean'],
                ['name' => 'taskReminders', 'type' => 'boolean'],
                ['name' => 'weeklyDigest', 'type' => 'boolean'],
                ['name' => 'teamUpdates', 'type' => 'boolean'],
                ['name' => 'soundEnabled', 'type' => 'boolean'],
                ['name' => 'sidebarOnRight', 'type' => 'boolean'],
                ['name' => 'showBottomBar', 'type' => 'boolean'],
            ]" />
        </x-api-endpoint>
        <x-api-endpoint method="GET" url="/api/settings/{settings}" summary="Get settings by ID" />
        <x-api-endpoint method="PUT" url="/api/settings/{settings}" summary="Update settings by ID">
            <x-api-body :fields="[
                ['name' => 'theme', 'type' => 'string'],
                ['name' => 'primaryColor', 'type' => 'string'],
                ['name' => 'fontFamily', 'type' => 'string'],
                ['name' => 'displayFont', 'type' => 'string'],
                ['name' => 'borderRadius', 'type' => 'string'],
                ['name' => 'sidebarCompact', 'type' => 'boolean'],
                ['name' => 'animationsEnabled', 'type' => 'boolean'],
                ['name' => 'fontSize', 'type' => 'string'],
                ['name' => 'language', 'type' => 'string'],
                ['name' => 'dateFormat', 'type' => 'string'],
                ['name' => 'timeFormat', 'type' => 'string'],
                ['name' => 'weekStartsOn', 'type' => 'string'],
                ['name' => 'emailNotifications', 'type' => 'boolean'],
                ['name' => 'pushNotifications', 'type' => 'boolean'],
                ['name' => 'taskReminders', 'type' => 'boolean'],
                ['name' => 'weeklyDigest', 'type' => 'boolean'],
                ['name' => 'teamUpdates', 'type' => 'boolean'],
                ['name' => 'soundEnabled', 'type' => 'boolean'],
                ['name' => 'sidebarOnRight', 'type' => 'boolean'],
                ['name' => 'showBottomBar', 'type' => 'boolean'],
            ]" />
        </x-api-endpoint>
        <x-api-endpoint method="DELETE" url="/api/settings/{settings}" summary="Delete settings by ID" />
    </div>
</div>
@endsection
