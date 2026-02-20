@extends('layouts.api-docs')
@section('title', 'Bidding API Documentation')
@section('content')
<div class=" mx-auto py-10">
    <h1 class="text-3xl font-bold mb-6 text-[#BA1A1A]">Bidding API Endpoints</h1>
    <div class="space-y-8">
        <x-api-endpoint method="GET" url="/api/bids" summary="List all bids" />
        <x-api-endpoint method="POST" url="/api/bids" summary="Place a new bid">
            <x-api-body :fields="[
                ['name' => 'auction_id', 'type' => 'integer'],
                ['name' => 'amount', 'type' => 'decimal'],
                ['name' => 'user_id', 'type' => 'integer'],
            ]" />
        </x-api-endpoint>
        <x-api-endpoint method="GET" url="/api/bids/{bid}" summary="Get bid by ID" />
        <x-api-endpoint method="PUT" url="/api/bids/{bid}" summary="Update bid by ID">
            <x-api-body :fields="[
                ['name' => 'amount', 'type' => 'decimal'],
            ]" />
        </x-api-endpoint>
        <x-api-endpoint method="DELETE" url="/api/bids/{bid}" summary="Delete bid by ID" />
        <x-api-endpoint method="GET" url="/api/auctions/{auction}/bids" summary="List all bids for an auction" />
    </div>
</div>
@endsection
