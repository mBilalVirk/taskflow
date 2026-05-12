@extends('layouts.app')
@section('title', 'Team Settings')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-3xl font-bold mb-8">Team Settings</h1>

            <!-- Settings Form -->
            <form method="POST" action="{{ route('team.update-settings', $team) }}" enctype="multipart/form-data"
                class="bg-white rounded-lg shadow p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Team Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Team Name</label>
                    <input type="text" name="name" value="{{ old('name', $team->name) }}" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="4"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description', $team->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Logo Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Team Logo</label>
                    <input type="file" name="logo" accept="image/*"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    @if ($team->logo)
                        <p class="text-sm text-gray-600 mt-2">Current logo:</p>
                        <img src="{{ asset('storage/' . $team->logo) }}" alt="Team Logo" class="h-20 mt-2">
                    @endif
                    @error('logo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
                        Save Changes
                    </button>
                </div>
            </form>

            <!-- Subscription Info -->
            <div class="mt-8 bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">Subscription</h2>
                <div class="space-y-2">
                    <p><strong>Plan:</strong> {{ ucfirst($team->subscription_plan) }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($team->subscription_status) }}</p>
                    <p><strong>Members:</strong> {{ $team->members()->count() }} / {{ $team->members_limit }}</p>
                </div>
                <a href="" class="mt-4 inline-block text-blue-500 hover:text-blue-700">
                    {{-- <a href="{{ route('billing.index', $team) }}" class="mt-4 inline-block text-blue-500 hover:text-blue-700"> --}}
                    Manage Subscription →
                </a>
            </div>
        </div>
    </div>
@endsection
