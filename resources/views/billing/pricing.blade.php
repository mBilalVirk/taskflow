@extends('layouts.app')
@section('title', 'Pricing ')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-12">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-5xl font-bold text-gray-900 mb-4">Simple, Transparent Pricing</h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Choose the perfect plan for your team. Always flexible to scale with your needs.
                </p>
            </div>

            <!-- Pricing Component -->
            @livewire('pricing-plans')
        </div>
    </div>
@endsection
