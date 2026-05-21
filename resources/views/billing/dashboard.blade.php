@extends('layouts.app')
@section('title', 'Billing & Subscriptions')

@section('content')
    <div class="max-w-5xl mx-auto p-6">
        <h1 class="text-4xl font-bold mb-8">Billing & Subscriptions</h1>

        <!-- Tabs -->
        <div class="mb-8 border-b">
            <div class="flex gap-8">
                <a href="#subscription" class="pb-4 border-b-2 border-blue-500 font-bold text-blue-600">
                    Current Plan
                </a>
                <a href="#invoices" class="pb-4 font-bold text-gray-600 hover:text-gray-900">
                    Invoices
                </a>
            </div>
        </div>

        <!-- Current Plan Section -->
        <div id="subscription">
            @livewire('billing-dashboard', ['team' => $team])
        </div>

        <!-- Invoices Section -->
        <div id="invoices" class="mt-12">
            <h2 class="text-2xl font-bold mb-6">Billing History</h2>
            @livewire('invoices-list', ['team' => $team])
        </div>
    </div>
@endsection
