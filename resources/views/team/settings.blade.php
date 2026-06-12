@extends('layouts.app')
@section('title', 'Team Settings')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-dark-bg">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-3xl font-bold mb-8 text-gray-900 dark:text-gray-400">Team Settings</h1>

            <form method="POST" action="{{ route('team.update-settings', $team) }}" enctype="multipart/form-data"
                class="bg-white rounded-lg shadow p-6 space-y-6 dark:bg-dark-card">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-400">Team Name</label>
                    <input type="text" name="name" value="{{ old('name', $team->name) }}" required
                        class="w-full border border-gray-300 bg-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 dark:bg-dark-bg dark:border-gray-700 dark:text-gray-300 dark:focus:ring-blue-600">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-400">Description</label>
                    <textarea name="description" rows="4"
                        class="w-full border border-gray-300 bg-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 dark:bg-dark-bg dark:border-gray-700 dark:text-gray-300 dark:focus:ring-blue-600">{{ old('description', $team->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-400">Team Logo</label>
                    <input type="file" name="logo" accept="image/*"
                        class="w-full border border-gray-300 bg-white rounded-lg px-4 py-2 text-gray-900 dark:bg-dark-bg dark:border-gray-700 dark:text-gray-400">
                    @if ($team->logo)
                        <p class="text-sm text-gray-600 mt-2 dark:text-gray-400">Current logo:</p>
                        <img src="{{ asset('storage/' . $team->logo) }}" alt="Team Logo"
                            class="h-20 mt-2 rounded border dark:border-gray-700">
                    @endif
                    @error('logo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700">
                        Save Changes
                    </button>
                </div>
            </form>

            <div class="mt-8 bg-white rounded-lg shadow p-6 dark:bg-dark-card">
                <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-400">Subscription</h2>
                <div class="space-y-2 text-gray-700 dark:text-gray-300">
                    <p><strong class="text-gray-900 dark:text-gray-400">Plan:</strong>
                        {{ ucfirst($team->subscription_plan) }}</p>
                    <p><strong class="text-gray-900 dark:text-gray-400">Status:</strong>
                        {{ ucfirst($team->subscription_status) }}</p>
                    <p><strong class="text-gray-900 dark:text-gray-400">Members:</strong> {{ $team->members()->count() }} /
                        {{ $team->members_limit }}</p>
                </div>
                <a href=""
                    class="mt-4 inline-block text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                    Manage Subscription →
                </a>
            </div>
        </div>
    </div>
@endsection
