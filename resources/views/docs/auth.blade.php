@extends('layouts.api-docs')
@section('title', 'Auth API Documentation')
@section('content')
<div class=" mx-auto py-10">
    <h1 class="text-3xl font-bold mb-6 text-[#BA1A1A]">Auth API Endpoints</h1>
    <div class="space-y-8">
        <x-api-endpoint method="POST" url="/api/auth/register/customer" summary="Register a new customer">
            <x-api-body :fields="[
                ['name' => 'name', 'type' => 'string', 'required' => true],
                ['name' => 'email', 'type' => 'string', 'required' => true],
                ['name' => 'password', 'type' => 'string', 'required' => true, 'note' => 'confirmed'],
                ['name' => 'phone', 'type' => 'string', 'required' => true],
                ['name' => 'country', 'type' => 'string'],
                ['name' => 'state', 'type' => 'string'],
                ['name' => 'address', 'type' => 'string'],
            ]" />
        </x-api-endpoint>
        <x-api-endpoint method="POST" url="/api/auth/register/vendor" summary="Register a new vendor (individual/company)">
            <x-api-body :fields="[
                ['name' => 'name', 'type' => 'string', 'required' => true],
                ['name' => 'email', 'type' => 'string', 'required' => true],
                ['name' => 'password', 'type' => 'string', 'required' => true, 'note' => 'confirmed'],
                ['name' => 'phone', 'type' => 'string', 'required' => true],
                ['name' => 'user_type', 'type' => 'string', 'required' => true, 'note' => 'vendor_individual|vendor_company'],
                ['name' => 'vendor_type', 'type' => 'string', 'required' => true, 'note' => 'moving_company|transportation|packing_material|manpower|storage|local_moving|international_moving'],
                ['name' => 'company_name', 'type' => 'string', 'note' => 'required if user_type=vendor_company'],
                ['name' => 'trade_license', 'type' => 'string', 'note' => 'required if user_type=vendor_company'],
                ['name' => 'emirates_id', 'type' => 'string', 'note' => 'required if vendor_type=moving_company'],
                ['name' => 'contact_person_name', 'type' => 'string', 'note' => 'required if user_type=vendor_company'],
                ['name' => 'contact_person_designation', 'type' => 'string'],
                ['name' => 'country', 'type' => 'string', 'required' => true],
                ['name' => 'state', 'type' => 'string', 'required' => true],
                ['name' => 'service_area', 'type' => 'array', 'required' => true],
                ['name' => 'address', 'type' => 'string', 'required' => true],
            ]" />
        </x-api-endpoint>
        <x-api-endpoint method="POST" url="/api/auth/login" summary="Login with email and password">
            <x-api-body :fields="[
                ['name' => 'email', 'type' => 'string', 'required' => true],
                ['name' => 'password', 'type' => 'string', 'required' => true],
            ]" />
        </x-api-endpoint>
        <x-api-endpoint method="POST" url="/api/auth/forgot-password" summary="Request password reset link">
            <x-api-body :fields="[
                ['name' => 'email', 'type' => 'string', 'required' => true],
            ]" />
        </x-api-endpoint>
        <x-api-endpoint method="POST" url="/api/auth/reset-password" summary="Reset password with token">
            <x-api-body :fields="[
                ['name' => 'token', 'type' => 'string', 'required' => true],
                ['name' => 'email', 'type' => 'string', 'required' => true],
                ['name' => 'password', 'type' => 'string', 'required' => true, 'note' => 'confirmed'],
            ]" />
        </x-api-endpoint>
        <x-api-endpoint method="POST" url="/api/auth/refresh" summary="Refresh JWT token (Requires Authorization header)" />
        <x-api-endpoint method="POST" url="/api/auth/logout" summary="Logout user (Requires Authorization header)" />
        <x-api-endpoint method="GET" url="/api/auth/profile" summary="Get authenticated user profile (Requires Authorization header)" />
        <x-api-endpoint method="PUT" url="/api/auth/profile" summary="Update user profile (Requires Authorization header)">
            <x-api-body :fields="[
                ['name' => 'name', 'type' => 'string'],
                ['name' => 'phone', 'type' => 'string'],
                ['name' => 'photo', 'type' => 'file'],
                ['name' => 'country', 'type' => 'string'],
                ['name' => 'state', 'type' => 'string'],
                ['name' => 'address', 'type' => 'string'],
                ['name' => 'company_name', 'type' => 'string'],
                ['name' => 'bio', 'type' => 'string'],
            ]" />
        </x-api-endpoint>
        <x-api-endpoint method="POST" url="/api/auth/verify-email" summary="Verify email with code (Requires Authorization header)">
            <x-api-body :fields="[
                ['name' => 'verification_code', 'type' => 'string', 'required' => true, 'note' => '6 chars'],
            ]" />
        </x-api-endpoint>
        <x-api-endpoint method="GET" url="/api/auth/vendor/status" summary="Check vendor approval status (Requires Authorization header)" />
    </div>
</div>
@endsection
